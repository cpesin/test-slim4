<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\PageController;
use Symfony\Component\Mime\Email;
use Slim\Flash\Messages;
use Symfony\Component\Mailer\Mailer;

class ContactController extends PageController {
    
    /** @var string $name */
    private $name;

    /** @var string $email */
    private $email;

    /** @var string $message */
    private $message;

    /** @var Messages $message */
    public $messages;

    /** @var Mailer $mailer */
    public $mailer;

    public function getRender(): void 
    {
        $messages = $this->messages->getMessages();

        if(\sizeof($messages)) {
            $this->data = [
                'messages' => $messages
            ];
        }

        $this->render();
    }

    public function sendForm($request): bool 
    {
        $params = $request->getParams()['form'];

        $this->name = $this->sanitize($params['name']);
        $this->email = $this->sanitize($params['email'], 'email');
        $this->message = $this->sanitize($params['message']);    

        if(true === $this->isValid())
        {
            $email = (new Email())
                ->from($this->email)
                ->to('hello@example.com')
                ->subject('Email de test')
                ->text('Name : '.$this->name.'\nMessage :\n'.$this->message.'\n')
                ->html('Name : '.$this->name.'<br>Message :<p>'.$this->message.'</p>');

            $this->mailer->send($email);

            $this->messages->addMessage('success', 'Votre message a bien été envoyé');
            
            return true;
        }

        foreach($this->msg as $message) {
            $this->messages->addMessageNow('error', $message);
        }

        $this->data = [
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message
        ];
        
        return false;
    }

    private function isValid(): bool
    {
        if(true === empty($this->name)) $this->msg[] = 'Veuillez renseigner votre nom';
        if(true === empty($this->email)) $this->msg[] = 'Veuillez renseigner votre adresse email';
        elseif(false === filter_var($this->email, FILTER_SANITIZE_EMAIL)) $this->msg[] = 'Veuillez renseigner une adresse email valide';
        if(true === empty($this->message)) $this->msg[] = 'Veuillez indiquer votre message';
        
        return (true === empty($this->msg)) ? true : false;
    }

    private function sanitize($data, $type = 'string'): string 
    {
        $data = trim($data);

        if('email' === $type) {
            $data = filter_var($data, FILTER_SANITIZE_EMAIL);
        } else {
            $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $data;
    }
}
