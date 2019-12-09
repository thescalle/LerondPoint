<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;



class newConvEntt{


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $name;


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $firstName;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }







}