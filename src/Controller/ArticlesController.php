<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\PageController;
use App\Repository\ArticleRepository;

class ArticlesController extends PageController {
    
    public function getRender(): void 
    {
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getPublishedArticles();

        $this->data = [
            'articles' => $articles,
        ];

        $this->render();
    }
}
