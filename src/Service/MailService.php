<?php

namespace App\Service;

use App\Repository\UserRepository;

class MailService
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function sendMail(string $sujet, String $message): string
    {
        //code pour envoyer un mail
        //$mail->setSubjet($sujet);
        //$mail->setMesssage($message);
        //$mail->send();

        $this->userRepository->find();

        dd($sujet);
    }
}
