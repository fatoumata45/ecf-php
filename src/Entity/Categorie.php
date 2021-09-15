<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $candidat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $collaborateurs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commercial;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $administrateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidat(): ?string
    {
        return $this->candidat;
    }

    public function setCandidat(string $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getCollaborateurs(): ?string
    {
        return $this->collaborateurs;
    }

    public function setCollaborateurs(string $collaborateurs): self
    {
        $this->collaborateurs = $collaborateurs;

        return $this;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

    public function setCommercial(string $commercial): self
    {
        $this->commercial = $commercial;

        return $this;
    }

    public function getAdministrateur(): ?string
    {
        return $this->administrateur;
    }

    public function setAdministrateur(string $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }
}
