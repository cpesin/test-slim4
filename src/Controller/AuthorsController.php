<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\PageController;
use App\Repository\AuthorRepository;

class AuthorsController extends PageController {
    
    public function getRender(): void 
    {
        $authorRepository = new AuthorRepository();
        $authors = $authorRepository->getAuthors();

        $this->data = [
            'authors' => $authors,
        ];

        $this->render();
    }
}