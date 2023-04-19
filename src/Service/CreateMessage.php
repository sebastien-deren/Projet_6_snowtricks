<?php

namespace App\Service;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;

class CreateMessage
{
    public function __construct(
        private MessageRepository $repository,
    ){}
    public function __invoke(Message $message, User $user, ?Figure $figure =null):void
    {
        $message
            ->setUser($user)
            ->setFigure($figure)
        ;
        $this->repository->save($message,true);
    }

}