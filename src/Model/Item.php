<?php

namespace SallePW\SlimApp\Model;

use DateTime;

final class Item
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var int */
    private $owner;

    /** @var string */
    private $description;

    /** @var float */
    private $price;

    /** @var int */
    private $product_image;

    /** @var string */
    private $category;

    /** @var string */
    private $is_active;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime */
    private $updatedAt;

    /**
     * User constructor.
     */
    public function __construct(string $title, string $owner, string $description, float $price, string $product_image, string $category,  bool $is_active, DateTime $createdAt, DateTime $updatedAt)
    {
        $this->title = $title;
        $this->owner = $owner;
        $this->description = $description;
        $this->price = $price;
        $this->product_image = $product_image;
        $this->category = $category;
        $this->is_active = $is_active;
        $this->createdAt = $createdAt;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $email
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getOwner(): int
    {
        return $this->owner;
    }

    /**
     * @param string $password
     */
    public function setOwner(int $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getProductImage(): string
    {
        return $this->product_image;
    }

    /**
     * @param int $product_image
     */
    public function setProductImage(string $product_image): void
    {
        $this->product_image = $product_image;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
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
}
