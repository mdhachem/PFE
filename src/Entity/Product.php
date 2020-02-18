<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Vich\Uploadable
 */
class Product
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
     * @Vich\UploadableField(mapping="product", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plan", inversedBy="products")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $plan;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductLike", mappedBy="product")
     */
    private $productLikes;

    public function __construct()
    {
        $this->productLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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
     * @return Collection|ProductLike[]
     */
    public function getProductLikes()
    {
        return $this->productLikes;
    }

    public function addProductLike(ProductLike $productLike): self
    {
        if (!$this->productLikes->contains($productLike)) {
            $this->productLikes[] = $productLike;
            $productLike->setProduct($this);
        }

        return $this;
    }

    public function removeProductLike(ProductLike $productLike): self
    {
        if ($this->productLikes->contains($productLike)) {
            $this->productLikes->removeElement($productLike);
            // set the owning side to null (unless already changed)
            if ($productLike->getProduct() === $this) {
                $productLike->setProduct(null);
            }
        }

        return $this;
    }

    public function isLikedByUser(User $user): bool
    {
        foreach ($this->productLikes as $like) {
            if ($like->getUser() === $user) return true;
        }

        return false;
    }
}
