<?php

declare(strict_types=1);

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Faker;
use App\Entity\Article;
use App\Entity\Author;
use Doctrine\ORM\EntityManager;

final class LoadFixturesCommand extends Command {
    private EntityManager $em;

    public function __construct()
    {
        parent::__construct();

        /** @var \DI\Container $container */
        $container = require_once __DIR__.'/../../config/bootstrap.php';

        $this->em = $container->get(EntityManager::class);
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('load:fixtures');
        $this->setDescription('Reset database and load fixtures');
        
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->resetDatabase();

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 8; ++$i) {
            $author = new Author();
            $author->setFirstname($faker->firstname());
            $author->setLastname($faker->lastname());
            $author->setEmail($faker->email());
            
            $this->em->persist($author);
        }

        $this->em->flush();

        $authors = $this->getAuthors();

        for ($i = 0; $i < 8; ++$i) {
            $article = new Article();
            $article->setTitle($faker->sentence($faker->numberBetween(3, 6)));
            $article->setText($faker->realText($maxNbChars = 500, $indexSize = 5));
            $article->setState($faker->boolean($chanceOfGettingTrue = 90));
            $article->setCreated($faker->dateTimeInInterval($startDate = '-2 years', $interval = '+1 year', $timezone = 'Europe/Paris'));
            $article->setUpdated($faker->dateTimeInInterval($startDate = '-6 months', $interval = '+3 months', $timezone = 'Europe/Paris'));

            /** @var Author $author */
            $author = $authors[random_int(0, 7)];

            $article->setAuthor($author);

            $this->em->persist($article);
        }

        $this->em->flush();

        $output->writeln(sprintf('<info>Fixtures loaded!</info>'));

        // The error code, 0 on success
        return 0;
    }
    
    public function resetDatabase(): void {
        $rsm = new ResultSetMapping();

        $this->execQuery('SET FOREIGN_KEY_CHECKS = 0', $rsm);
        $this->execQuery('TRUNCATE '.$this->em->getClassMetadata(Article::class)->getTableName(), $rsm);
        $this->execQuery('TRUNCATE '.$this->em->getClassMetadata(Author::class)->getTableName(), $rsm);
        $this->execQuery('SET FOREIGN_KEY_CHECKS = 1', $rsm);
    }

    public function execQuery(string $query, ResultSetMapping $rsm): void
    {
        $query = $this->em->createNativeQuery($query, $rsm);
        $query->execute();
    }

    public function getAuthors(): mixed 
    {  
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('a')->from(Author::class, 'a');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
