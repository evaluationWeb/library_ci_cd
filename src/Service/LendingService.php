<?php

namespace App\Service;

use App\Entity\Lending;
use App\Repository\BookRepository;
use App\Repository\LendingRepository;
use App\Repository\UserRepository;

class LendingService
{
    private LendingRepository $lendingRepository;
    private BookRepository $bookRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->lendingRepository = new LendingRepository();
        $this->bookRepository = new BookRepository();
        $this->userRepository = new UserRepository();
    }

    public function saveLending(array $post, int $userId): array
    {
        $mandatoryAt = trim($post["mandatory_at"] ?? "");
        $bookId = (int) ($post["book_id"] ?? 0);
        $errors = [];

        if ($mandatoryAt === "") {
            $errors["mandatory_at"] = "La date limite est obligatoire";
        }
        if ($bookId === 0) {
            $errors["book_id"] = "Le livre est obligatoire";
        }
        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        $book = $this->bookRepository->find($bookId);
        $user = $this->userRepository->find($userId);

        if ($book === null || $user === null) {
            return ["errors" => ["_form" => "Le livre ou l'utilisateur est introuvable"]];
        }

        if (!$book->isAvailable()) {
            return ["errors" => ["_form" => "Ce livre est deja emprunte"]];
        }

        $lending = new Lending();
        $lending->setBook($book);
        $lending->setUser($user);
        $currentDate = new \DateTime();
        $lending->setLendAt($currentDate);
        $lending->setReturnAt(clone $currentDate);
        $lending->setMandatoryAt(new \DateTime($mandatoryAt));

        $savedLending = $this->lendingRepository->create($lending);

        if ($savedLending->getId() === 0) {
            return ["errors" => ["_form" => "Erreur lors de l'ajout de l'emprunt"]];
        }

        $this->bookRepository->updateAvailability($book->getId(), false);

        return [
            "message" => "L'emprunt a ete ajoute en BDD",
            "lending" => $savedLending,
        ];
    }

    public function getAllLendings(): array
    {
        return $this->lendingRepository->findAll();
    }

    public function getAllBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    public function getAvailableBooks(): array
    {
        return $this->bookRepository->findAvailable();
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function returnLending(int $id): bool
    {
        $lending = $this->lendingRepository->find($id);

        if ($lending === null) {
            return false;
        }

        $bookId = $lending->getBook()->getId();

        if (!$this->lendingRepository->delete($id)) {
            return false;
        }

        return $this->bookRepository->updateAvailability($bookId, true);
    }
}
