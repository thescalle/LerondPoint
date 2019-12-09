<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;



class MessagerieMsgEntt{


    /**
     * @var string|null
     * @Assert\lenght(min=2, max=900)
     */
    private $message;

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }



}