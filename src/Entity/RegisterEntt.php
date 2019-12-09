<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


class RegisterEntt
{

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string|null
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string|null $mail
     */
    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return string|null
     */
    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    /**
     * @param string|null $mdp
     */
    public function setMdp(?string $mdp): void
    {
        $this->mdp = $mdp;
    }

    /**
     * @return string|null
     */
    public function getMdpConfirm(): ?string
    {
        return $this->mdpConfirm;
    }

    /**
     * @param string|null $mdpConfirm
     */
    public function setMdpConfirm(?string $mdpConfirm): void
    {
        $this->mdpConfirm = $mdpConfirm;
    }

    /**
     * @return bool|null
     */
    public function getAccept(): ?bool
    {
        return $this->accept;
    }

    /**
     * @param bool|null $accept
     */
    public function setAccept(?bool $accept): void
    {
        $this->accept = $accept;
    }

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $nom;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $prenom;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $mail;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $mdp;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $mdpConfirm;

    /**
     * @var bool|null
     * @Assert\NotBlank()
     * @Assert\lenght(min=2, max=300)
     */
    private $accept;
}