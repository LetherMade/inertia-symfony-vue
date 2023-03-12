<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ORM\Index(columns: ['name'], name: 'name_idx')]
#[ORM\Index(columns: ['price'], name: 'price_idx')]
#[UniqueEntity(fields: ['name'], entityClass: Car::class)]
class Car
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private string $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    private int $price;

    #[ORM\Column]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    #[Assert\NotBlank]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function setName(?string $name): self
    {
        if (null === $name) {
            unset($this->name);

            return $this;
        }

        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price ?? null;
    }

    public function setPrice(?int $price): self
    {
        if (null === $price) {
            unset($this->price);

            return $this;
        }

        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'createdAt' => $this->getCreatedAt()->format('d-m-Y H:i:s'),
        ];
    }
}
