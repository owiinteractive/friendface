<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProfileRepository")
 */
class UserProfile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserProfile", inversedBy="friendsReceived")
     * @ORM\JoinTable(name="friendship",
     * joinColumns={
     *     @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     *   }
     * )
     */
    private $friendsRequested;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserProfile", mappedBy="friendsRequested")
     */
    private $friendsReceived;

    public function __construct()
    {
        $this->friendsRequested = new ArrayCollection();
        $this->friendsReceived = new ArrayCollection();
    }

    public function getFriends(): ?array
    {
        return array_merge(
            $this->getFriendsRequested()->toArray(),
            $this->getFriendsReceived()->toArray()
        );
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
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
     * @return Collection|self[]
     */
    public function getFriendsRequested(): Collection
    {
        return $this->friendsRequested;
    }

    public function addFriendsRequested(self $friendsRequested): self
    {
        if (!$this->friendsRequested->contains($friendsRequested)) {
            $this->friendsRequested[] = $friendsRequested;
        }

        return $this;
    }

    public function removeFriendsRequested(self $friendsRequested): self
    {
        if ($this->friendsRequested->contains($friendsRequested)) {
            $this->friendsRequested->removeElement($friendsRequested);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriendsReceived(): Collection
    {
        return $this->friendsReceived;
    }

    public function addFriendsReceived(self $friendsReceived): self
    {
        if (!$this->friendsReceived->contains($friendsReceived)) {
            $this->friendsReceived[] = $friendsReceived;
            $friendsReceived->addFriendsRequested($this);
        }

        return $this;
    }

    public function removeFriendsReceived(self $friendsReceived): self
    {
        if ($this->friendsReceived->contains($friendsReceived)) {
            $this->friendsReceived->removeElement($friendsReceived);
            $friendsReceived->removeFriendsRequested($this);
        }

        return $this;
    }

}
