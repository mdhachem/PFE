<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Vich\Uploadable
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="event", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEvent;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plan", inversedBy="events")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $plan;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventLike", mappedBy="event")
     */
    private $eventLikes;

    public function __construct()
    {
        $this->eventLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return Collection|EventLike[]
     */
    public function getEventLikes()
    {
        return $this->eventLikes;
    }

    public function addEventLike(EventLike $eventLike): self
    {
        if (!$this->eventLikes->contains($eventLike)) {
            $this->eventLikes[] = $eventLike;
            $eventLike->setEvent($this);
        }

        return $this;
    }

    public function removeEventLike(EventLike $eventLike): self
    {
        if ($this->eventLikes->contains($eventLike)) {
            $this->eventLikes->removeElement($eventLike);
            // set the owning side to null (unless already changed)
            if ($eventLike->getEvent() === $this) {
                $eventLike->setEvent(null);
            }
        }

        return $this;
    }

    public function isLikedByUser(User $user): bool
    {
        foreach ($this->eventLikes as $like) {
            if ($like->getUser() === $user) return true;
        }

        return false;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }
}
