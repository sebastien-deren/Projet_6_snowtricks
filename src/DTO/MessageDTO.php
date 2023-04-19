<?php

namespace App\DTO;

use App\Entity\Message;

class MessageDTO
{
    public string $user;
    public string $content;
    public string $date;
    public string $photo;

    public function __construct(Message $message)
    {
        $this->user = $message->getUser()->getUsername();
        $this->date = $message->getCreatedAt()->format('Y-m-d');
        $this->content = $message->getContent();
        $this->photo = $message->getUser()->getPhoto();

    }

}
