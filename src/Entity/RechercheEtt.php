<?php


namespace App\Entity;


class RechercheEtt
{
    /**
     * @var string|null
     * @Assert\lenght(min=0, max=50)
     */
    private $searchString;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=0, max=50)
     */
    private $region;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=0, max=50)
     */
    private $categorie;

    /**
     * @return string|null
     */
    public function getSearchString(): ?string
    {
        return $this->searchString;
    }

    /**
     * @param string|null $searchString
     */
    public function setSearchString(?string $searchString): void
    {
        $this->searchString = $searchString;
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

    /**
     * @return string|null
     */
    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    /**
     * @param string|null $categorie
     */
    public function setCategorie(?string $categorie): void
    {
        $this->categorie = $categorie;
    }





}