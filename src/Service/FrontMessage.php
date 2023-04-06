<?php

namespace App\Service;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\Collection;


class FrontMessage
{

    public function __construct(private MessageRepository $repository){}

    /**
     * @return Message[]
     */
    public function __invoke():array
    {
         return $this->repository->findBy(["figure" => null]);
    }
}