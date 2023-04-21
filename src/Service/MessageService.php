<?php

namespace App\Service;

use App\DTO\MessageDTO;
use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ReadableCollection;

class MessageService
{
    public function __construct(private MessageRepository $repository)
    {
    }

    /**
     * @return Message[]
     */
    public function displayFront(int $itemByPage =0): array
    {
        $messages = $this->repository->findBy(["figure" => null],["createdAt"=>"DESC"]);
        $messages=  array_map((fn($args) => new MessageDTO($args)),$messages);
        if(0===$itemByPage){
            return [0=>$messages];
        }
        return array_chunk($messages,$itemByPage);
    }

    public function displayFigure(array $messages,int $itemByPage =0 ): array
    {
        if(0===$itemByPage){
            return [0=>$messages];
        }
        return array_chunk($messages,$itemByPage);
    }
    public function create(Message $message, User $user, Figure $figure = null): void
    {
        $message
            ->setUser($user)
            ->setFigure($figure);
        $this->repository->save($message, true);
    }

}
