<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @Vich\Uploadable
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photoName;

    /**
     * @Vich\UploadableField(mapping="photo", fileNameProperty="photoName")
     */
    private $imageFile;


    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plan", inversedBy="photos")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $plan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhotoName()
    {
        return $this->photoName;
    }

    public function setPhotoName($photoName): self
    {
        $this->photoName = $photoName;

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
        $this->createdAt = new \DateTime();
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function __toString()
    {
        return $this->photoName;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription(String $description)
    {
        $this->description = $description;

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
}
