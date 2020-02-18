<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     */
    private $emitter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     */
    private $receivere;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isViewed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getEmitter(): ?User
    {
        return $this->emitter;
    }

    public function setEmitter($emitter): self
    {
        $this->emitter = $emitter;

        return $this;
    }

    public function getReceivere(): ?User
    {
        return $this->receivere;
    }

    public function setReceivere($receivere): self
    {
        $this->receivere = $receivere;

        return $this;
    }

    public function getIsViewed(): ?bool
    {
        return $this->isViewed;
    }

    public function setIsViewed(bool $isViewed): self
    {
        $this->isViewed = $isViewed;

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
