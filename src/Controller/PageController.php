<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Symfony\Component\Mailer\Mailer;

class PageController {
    /** @var string $template */
    public $template;

    /** @var Request $request */
    public $request;
    
    /** @var Response $response */
    public $response;
    
    /** @var int $status */
    public $status;

    /** @var array $msg */
    public $msg;
    
    /** @var array<string, mixed> $data */
    public $data;

    /** @var Twig $view */
    public $view;

    /** @var Messages $messages */
    public $messages;

    /** @var Mailer $mailer */
    public $mailer;

    public function __construct(Twig $view, Messages $messages = null, Mailer $mailer = null)    
    {
        $this->view = $view;
        $this->messages = $messages;
        $this->mailer = $mailer;
        $this->status = 200;
        $this->data = [];
        $this->msg = [];
    }

    public function init(string $template, Request $request, Response $response): void 
    {
        $this->template = 'pages/'.$template;
        $this->request = $request;
        $this->response = $response;
    }

    public function render(): Response {
        return $this->view->render($this->response, $this->template.'.twig', $this->data);
    }
}