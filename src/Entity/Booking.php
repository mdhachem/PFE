<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", length=255, nullable=true)
     */
    private $finalDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $guests;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $RoomType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $menu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plan", inversedBy="bookings")
     */
    private $plan;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFinalDate()
    {
        return $this->finalDate;
    }

    public function setFinalDate($finalDate): self
    {
        $this->finalDate = $finalDate;

        return $this;
    }

    public function getGuests(): ?int
    {
        return $this->guests;
    }

    public function setGuests(?int $guests): self
    {
        $this->guests = $guests;

        return $this;
    }

    public function getRoomType(): ?string
    {
        return $this->RoomType;
    }

    public function setRoomType(?string $RoomType): self
    {
        $this->RoomType = $RoomType;

        return $this;
    }

    public function getMenu(): ?string
    {
        return $this->menu;
    }

    public function setMenu(?string $menu): self
    {
        $this->menu = $menu;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
