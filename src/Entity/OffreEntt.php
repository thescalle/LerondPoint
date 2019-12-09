<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class OffreEntt{

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $category;


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $title;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $description;


    /**
     * @var integer|null
     * @Assert\NotBlank()
     */
    private $price;

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
    private $address;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $region;


    /**
     * @var string|null
     * @Assert\lenght(min=2, max=300)
     */
    private $image1;


    /**
     * @var string|null
     * @Assert\lenght(min=2, max=300)
     */
    private $image2;




    /**
     * @var string|null
     * @Assert\lenght(min=2, max=300)
     */
    private $image3;

    /**
     * @return string|null
     */
    public function getImage1(): ?string
    {
        return $this->image1;
    }

    /**
     * @param string|null $image1
     */
    public function setImage1(?string $image1): void
    {
        $this->image1 = $image1;
    }

    /**
     * @return string|null
     */
    public function getImage2(): ?string
    {
        return $this->image2;
    }

    /**
     * @param string|null $image2
     */
    public function setImage2(?string $image2): void
    {
        $this->image2 = $image2;
    }

    /**
     * @return string|null
     */
    public function getImage3(): ?string
    {
        return $this->image3;
    }

    /**
     * @param string|null $image3
     */
    public function setImage3(?string $image3): void
    {
        $this->image3 = $image3;
    }




    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
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
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string|null $region
     */
    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }



}