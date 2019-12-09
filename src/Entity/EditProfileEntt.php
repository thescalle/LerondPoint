<?php


namespace App\Entity;



class EditProfileEntt{

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
    private $address;


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $city;


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $phone;

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
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }


}
