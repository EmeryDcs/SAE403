<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\Column(length: 255)]
    private ?string $acquis = null;

    #[ORM\Column(length: 255)]
    private ?string $domaine = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video = null;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'id_projet')]
    private ?Note $notes = null;

    #[ORM\ManyToOne(inversedBy: 'id_projet')]
    private ?Commentaire $commentaires = null;

    #[ORM\ManyToMany(targetEntity: Competence::class)]
    private Collection $competences;

    #[ORM\ManyToMany(targetEntity: AC::class)]
    private Collection $ac;

    #[ORM\OneToMany(mappedBy: 'projets', targetEntity: Utilisateur::class)]
    private Collection $utilisateur;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->ac = new ArrayCollection();
        $this->utilisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getAcquis(): ?string
    {
        return $this->acquis;
    }

    public function setAcquis(string $acquis): self
    {
        $this->acquis = $acquis;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

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

    public function getNotes(): ?Note
    {
        return $this->notes;
    }

    public function setNotes(?Note $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCommentaires(): ?Commentaire
    {
        return $this->commentaires;
    }

    public function setCommentaires(?Commentaire $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * @return Collection<int, Competence>
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences->add($competence);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competences->removeElement($competence);

        return $this;
    }

    /**
     * @return Collection<int, AC>
     */
    public function getAc(): Collection
    {
        return $this->ac;
    }

    public function addAc(AC $ac): self
    {
        if (!$this->ac->contains($ac)) {
            $this->ac->add($ac);
        }

        return $this;
    }

    public function removeAc(AC $ac): self
    {
        $this->ac->removeElement($ac);

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateur(): Collection
    {
        return $this->utilisateur;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateur->contains($utilisateur)) {
            $this->utilisateur->add($utilisateur);
            $utilisateur->setProjets($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateur->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getProjets() === $this) {
                $utilisateur->setProjets(null);
            }
        }

        return $this;
    }
}
