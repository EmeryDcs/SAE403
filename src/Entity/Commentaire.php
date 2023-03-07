<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'commentaires', targetEntity: Projet::class)]
    private Collection $id_projet;

    #[ORM\OneToMany(mappedBy: 'commentaires', targetEntity: Utilisateur::class)]
    private Collection $id_user;

    #[ORM\Column(length: 255)]
    private ?string $texte = null;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    public function __construct()
    {
        $this->id_projet = new ArrayCollection();
        $this->id_user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getIdProjet(): Collection
    {
        return $this->id_projet;
    }

    public function addIdProjet(Projet $idProjet): self
    {
        if (!$this->id_projet->contains($idProjet)) {
            $this->id_projet->add($idProjet);
            $idProjet->setCommentaires($this);
        }

        return $this;
    }

    public function removeIdProjet(Projet $idProjet): self
    {
        if ($this->id_projet->removeElement($idProjet)) {
            // set the owning side to null (unless already changed)
            if ($idProjet->getCommentaires() === $this) {
                $idProjet->setCommentaires(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getIdUser(): Collection
    {
        return $this->id_user;
    }

    public function addIdUser(Utilisateur $idUser): self
    {
        if (!$this->id_user->contains($idUser)) {
            $this->id_user->add($idUser);
            $idUser->setCommentaires($this);
        }

        return $this;
    }

    public function removeIdUser(Utilisateur $idUser): self
    {
        if ($this->id_user->removeElement($idUser)) {
            // set the owning side to null (unless already changed)
            if ($idUser->getCommentaires() === $this) {
                $idUser->setCommentaires(null);
            }
        }

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
