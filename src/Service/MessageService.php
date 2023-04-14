<?php

namespace App\Service;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;

class MessageService
{
    public function __construct(private MessageRepository $repository)
    {
    }

    /**
     * @return Message[]
     */
    public function DisplayFront(): array
    {
        return $this->repository->findBy(["figure" => null]);
    }

    public function create(Message $message, User $user, Figure $figure = null): void
    {
        $message
            ->setUser($user)
            ->setFigure($figure);
        $this->repository->save($message, true);
    }

}
