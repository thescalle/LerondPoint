<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;




class LoginEntt{


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $email;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $password;




    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }


    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }





}