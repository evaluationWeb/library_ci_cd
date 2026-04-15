<?php

namespace App\Entity;

class Category
{
    private int $id;
    private string $name;

    // Getters and setters for each property
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNormalizedName(): string
    {
        return mb_strtolower(trim($this->name));
    }
}
