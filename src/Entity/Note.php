<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'notes', targetEntity: Utilisateur::class)]
    private Collection $id_user;

    #[ORM\OneToMany(mappedBy: 'notes', targetEntity: Projet::class)]
    private Collection $id_projet;

    #[ORM\Column]
    private ?int $note = null;

    public function __construct()
    {
        $this->id_user = new ArrayCollection();
        $this->id_projet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $idUser->setNotes($this);
        }

        return $this;
    }

    public function removeIdUser(Utilisateur $idUser): self
    {
        if ($this->id_user->removeElement($idUser)) {
            // set the owning side to null (unless already changed)
            if ($idUser->getNotes() === $this) {
                $idUser->setNotes(null);
            }
        }

        return $this;
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
            $idProjet->setNotes($this);
        }

        return $this;
    }

    public function removeIdProjet(Projet $idProjet): self
    {
        if ($this->id_projet->removeElement($idProjet)) {
            // set the owning side to null (unless already changed)
            if ($idProjet->getNotes() === $this) {
                $idProjet->setNotes(null);
            }
        }

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
