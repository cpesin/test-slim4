<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\PageController;
use App\Repository\ArticleRepository;

class IndexController extends PageController {
    
    public function getRender(): void
    {
        $articleRepository = new ArticleRepository();
        $lastArticle = $articleRepository->getLastPublishedArticle();

        $this->data = [
            'lastArticle' => $lastArticle,
            'readme' => $this->getReadMe(),
        ];

        $this->render();
    }

    private function getReadMe(): string
    {
        $readme = file_get_contents(__DIR__.'/../../README.md');
        $readme = false === $readme ? '' : $readme;

        return $readme;
    }
}