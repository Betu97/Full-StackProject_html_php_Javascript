<?php

namespace SallePW\SlimApp\Model;

use DateTime;

final class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $username;

    /** @var string */
    private $email;

    /** @var DateTime */
    private $birthdate;

    /** @var int */
    private $phone_number;

    /** @var string */
    private $password;

    /** @var bool */
    private $is_active;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime */
    private $updatedAt;

    /**
     * User constructor.
     * @param string $email
     * @param string $password
     * @param string $name
     * @param DateTime $createdAt
     * @param DateTime $updatedAt
     */
    public function __construct(string $name, string $username, string $email, DateTime $birthdate, String $phone_number, string $password, bool $is_active, DateTime $createdAt, DateTime $updatedAt)
    {
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->phone_number = $phone_number;
        $this->password = $password;
        $this->is_active = $is_active;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return DateTime
     */
    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime $birthdate
     */
    public function setBirthdate(DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return String
     */
    public function getPhoneNumber(): String
    {
        return $this->phone_number;
    }

    /**
     * @param int $phone_number
     */
    public function setPhoneNumber(String $phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getIsActive(): string
    {
        return $this->is_active;
    }

    /**
     * @param string $is_active
     */
    public function setIsActive(string $is_active): void
    {
        $this->is_active = $is_active;
    }
}
