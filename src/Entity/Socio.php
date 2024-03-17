<?php

namespace App\Entity;

use App\Repository\SocioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocioRepository::class)]
class Socio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $cpf = null;

    #[ORM\Column]
    private ?int $empresa_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCPF(): ?string
    {
        return $this->cpf;
    }

    public function setCPF(string $cpf): static
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getEmpresa(): ?int
    {
        return $this->empresa_id;
    }

    public function setEmpresa(int $empresa): static
    {
        $this->empresa_id = $empresa;

        return $this;
    }
}
