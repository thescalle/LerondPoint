<?php


namespace App\Entity;




class ChangePasswordEntt{

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=5, max=50)
     */
    private $email;


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=5, max=50)
     */
    private $emailTwo;



    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=5, max=50)
     */
    private $password;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=5, max=50)
     */
    private $passwordTwo;

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

    /**
     * @return string|null
     */
    public function getEmailTwo(): ?string
    {
        return $this->emailTwo;
    }

    /**
     * @param string|null $emailTwo
     */
    public function setEmailTwo(?string $emailTwo): void
    {
        $this->emailTwo = $emailTwo;
    }

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
    public function getPasswordTwo(): ?string
    {
        return $this->passwordTwo;
    }

    /**
     * @param string|null $passwordTwo
     */
    public function setPasswordTwo(?string $passwordTwo): void
    {
        $this->passwordTwo = $passwordTwo;
    }






}