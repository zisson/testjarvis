<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     */
    public $datecreate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDatecreate(): ?DateTime
    {
        return $this->datecreate;
    }

    public function setDatecreate(DateTime $datecreate): self
    {
        $this->datecreate = $datecreate;

        return $this;
    }

    public function getUpdatedate(): ?DateTime
    {
        return $this->updatedate;
    }

    public function setUpdatedate(DateTime $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }


}
