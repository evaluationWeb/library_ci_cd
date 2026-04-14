<?php

namespace App\Entity;

class Book
{
    private int $id;
    private string $title;
    private string $author;
    private string $description;
    private string $cover;
    private \DateTime $publishAt;
    private bool $isAvailable;
    private array $categories;

    public function __construct()
    {
        $this->categories = [];
    }

    // Getters and setters for each property
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function getAuthor(): string
    {
        return $this->author;
    }
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    public function getCover(): string
    {
        return $this->cover;
    }
    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }
    public function getPublishAt(): \DateTime
    {
        return $this->publishAt;
    }
    public function setPublishAt(\DateTime $publishAt): void
    {
        $this->publishAt = $publishAt;
    }
    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }
    public function setIsAvailable(bool $isAvailable): void
    {
        $this->isAvailable = $isAvailable;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
    }

    public function removeCategory(Category $category): void
    {
        unset($this->categories[array_search($category, $this->categories)]);
        sort($this->categories);
    }
}
