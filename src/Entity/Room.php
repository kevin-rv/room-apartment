<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"apartment"})
     * @Groups({"room"})
     * @Groups({"reservation"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"room"})
     * @Groups({"reservation"})
     */
    private $number;

    /**
     * @ORM\Column(type="float")
     * @Groups({"room"})
     */
    private $area;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"room"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"room"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="room")
     */
    private $reservation;

    /**
     * @ORM\ManyToOne(targetEntity=Apartment::class, inversedBy="rooms")
     * @Groups({"room"})
     */
    private $apartment;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(float $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation[] = $reservation;
            $reservation->setRoom($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getRoom() === $this) {
                $reservation->setRoom(null);
            }
        }

        return $this;
    }

    public function getApartment(): ?apartment
    {
        return $this->apartment;
    }

    public function setApartment(?apartment $apartment): self
    {
        $this->apartment = $apartment;

        return $this;
    }

}
