<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields = {"email"},
 * message = "L'email que vous avez indique est deja utilise !"
 * )
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas tape le meme mot de passe ")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;


    /**
     * @Vich\UploadableField(mapping="user", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var string
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plan", mappedBy="user", orphanRemoval=true)
     */
    private $plans;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventLike", mappedBy="user")
     */
    private $eventLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductLike", mappedBy="user")
     */
    private $productLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="user")
     */
    private $ratings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="user")
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="emitter")
     */
    private $messages;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->plans = new ArrayCollection();
        $this->eventLikes = new ArrayCollection();
        $this->productLikes = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        return [$this->roles];
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getSlug(): ?string
    {
        return (new Slugify())->slugify($this->firstName . '-' . $this->lastName);
    }

    /**
     * @return Collection|Plan[]
     */
    public function getPlans()
    {
        return $this->plans;
    }

    public function addPlan(Plan $plan): self
    {
        if (!$this->plans->contains($plan)) {
            $this->plans[] = $plan;
            $plan->setUser($this);
        }

        return $this;
    }

    public function removePlan(Plan $plan): self
    {
        if ($this->plans->contains($plan)) {
            $this->plans->removeElement($plan);
            // set the owning side to null (unless already changed)
            if ($plan->getUser() === $this) {
                $plan->setUser(null);
            }
        }

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;


        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function __toString()
    {
        return $this->lastName . ' ' . $this->firstName;
    }


    public function getToken()
    {
        return $this->token;
    }


    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     *
     * Serialize l'utilisateur pour le stocker en Session
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password
        ));
    }

    /**
     *
     * Permet de recharger l'utilisateur à partir de la session
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
        ) = unserialize($serialized);
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
            $eventLike->setUser($this);
        }

        return $this;
    }

    public function removeEventLike(EventLike $eventLike): self
    {
        if ($this->eventLikes->contains($eventLike)) {
            $this->eventLikes->removeElement($eventLike);
            // set the owning side to null (unless already changed)
            if ($eventLike->getUser() === $this) {
                $eventLike->setUser(null);
            }
        }

        return $this;
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
            $productLike->setUser($this);
        }

        return $this;
    }

    public function removeProductLike(ProductLike $productLike): self
    {
        if ($this->productLikes->contains($productLike)) {
            $this->productLikes->removeElement($productLike);
            // set the owning side to null (unless already changed)
            if ($productLike->getUser() === $this) {
                $productLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setUser($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getUser() === $this) {
                $booking->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setEmitter($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getEmitter() === $this) {
                $message->setEmitter(null);
            }
        }

        return $this;
    }
}
