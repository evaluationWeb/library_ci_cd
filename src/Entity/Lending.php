<?php

namespace App\Entity;

class Lending
{
    private int $id;
    private User $user;
    private Book $book;
    private \DateTime $lendAt;
    private \DateTime $returnAt;
    private \DateTime $mandatoryAt;

    // Getters and setters for each property
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    public function getBook(): Book
    {
        return $this->book;
    }
    public function setBook(Book $book): void
    {
        $this->book = $book;
    }
    public function getLendAt(): \DateTime
    {
        return $this->lendAt;
    }
    public function setLendAt(\DateTime $lendAt): void
    {
        $this->lendAt = $lendAt;
    }
    public function getReturnAt(): \DateTime
    {
        return $this->returnAt;
    }
    public function setReturnAt(\DateTime $returnAt): void
    {
        $this->returnAt = $returnAt;
    }
    public function getMandatoryAt(): \DateTime
    {
        return $this->mandatoryAt;
    }
    public function setMandatoryAt(\DateTime $mandatoryAt): void
    {
        $this->mandatoryAt = $mandatoryAt;
    }
}
