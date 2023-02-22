<?php

declare(strict_types=1);

use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use DI\Container;
use Slim\Views\Twig;
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;
use Twig\Extra\Intl\IntlExtension;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Slim\Flash\Messages;
use Psr\Container\ContainerInterface;
use App\Controller\IndexController;
use App\Controller\ArticlesController;
use App\Controller\AuthorsController;
use App\Controller\ContactController;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container(require __DIR__ . '/../settings.php');

// Set Entity manager
$container->set(EntityManager::class, static function (Container $c): EntityManager {
    /** @var array $settings */
    $settings = $c->get('settings');

    // Use the ArrayAdapter or the FilesystemAdapter depending on the value of the 'dev_mode' setting
    // You can substitute the FilesystemAdapter for any other cache you prefer from the symfony/cache library
    $cache = $settings['doctrine']['dev_mode'] ?
        DoctrineProvider::wrap(new ArrayAdapter()) :
        DoctrineProvider::wrap(new FilesystemAdapter(directory: $settings['doctrine']['cache_dir']));

    $config = Setup::createAttributeMetadataConfiguration(
        $settings['doctrine']['metadata_dirs'],
        $settings['doctrine']['dev_mode'],
        null,
        $cache
    );

    return EntityManager::create($settings['doctrine']['connection'], $config);
});

// Set view in Container
$container->set('view', function(): Twig {
    $twig = Twig::create(__DIR__.'/../templates/', ['cache' => false]);
    // Add extensions explicitly on the Twig environment
    $twig->addExtension(new IntlExtension());
    $twig->addExtension(new MarkdownExtension());
    // Register the extension runtime
    $twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
        public function load(mixed $class): MarkdownRuntime|null {
            if (MarkdownRuntime::class === $class) {
                return new MarkdownRuntime(new DefaultMarkdown());
            }
            return null;
        }
    });
    return $twig;
});

// Set mailer in Container
$container->set('mailer', function(): Mailer {
    $transport = Transport::fromDsn('smtp://mailer:1025');
    $mailer = new Mailer($transport);
    return $mailer;
});

// Set mailer in Container
$container->set('messages', function(): Messages {
    return new Messages();
});


$container->set('IndexController', function (ContainerInterface $container) {
    $view = $container->get('view');
    
    return new IndexController($view);
});

$container->set('ArticlesController', function (ContainerInterface $container) {
    $view = $container->get('view');
    
    return new ArticlesController($view);
});

$container->set('AuthorsController', function (ContainerInterface $container) {
    $view = $container->get('view');
    
    return new AuthorsController($view);
});

$container->set('ContactController', function (ContainerInterface $container) {
    $view = $container->get('view');
    $messages = $container->get('messages');
    $mailer = $container->get('mailer');
    
    return new ContactController($view, $messages, $mailer);
});

return $container;
