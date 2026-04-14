<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Service\Exception\UploadException;

class BookService
{
    private BookRepository $bookRepository;
    private CategoryRepository $categoryRepository;
    private UploadService $uploadService;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->uploadService = new UploadService();
    }

    public function saveBook(array $post, array $files = []): array
    {
        $title = trim($post["title"] ?? "");
        $author = trim($post["author"] ?? "");
        $description = trim($post["description"] ?? "");
        $publishAt = trim($post["publish_at"] ?? "");
        $categoryIds = $post["categories"] ?? [];
        $errors = [];

        if ($title === "") {
            $errors["title"] = "Le titre est obligatoire";
        }
        if ($author === "") {
            $errors["author"] = "L'auteur est obligatoire";
        }
        if ($description === "") {
            $errors["description"] = "La description est obligatoire";
        }
        if ($publishAt === "") {
            $errors["publish_at"] = "La date de publication est obligatoire";
        }

        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setDescription($description);
        $book->setPublishAt(new \DateTime($publishAt));
        $book->setIsAvailable(isset($post["available"]));

        try {
            $cover = $this->uploadService->uploadFile($files["cover"] ?? []);
        } catch (UploadException $e) {
            $cover = "";

            if (($files["cover"]["tmp_name"] ?? "") !== "") {
                return ["errors" => ["cover" => $e->getMessage()]];
            }
        }

        $book->setCover($cover);

        if (is_array($categoryIds)) {
            foreach ($categoryIds as $categoryId) {
                $category = $this->categoryRepository->find((int) $categoryId);

                if ($category !== null) {
                    $book->addCategory($category);
                }
            }
        }

        $savedBook = $this->bookRepository->create($book);

        if ($savedBook->getId() === 0) {
            return ["errors" => ["_form" => "Erreur lors de l'ajout du livre"]];
        }

        return [
            "message" => "Le livre a ete ajoute en BDD",
            "book" => $savedBook,
        ];
    }

    public function updateBook(int $id, array $post, array $files = []): array
    {
        $book = $this->bookRepository->find($id);

        if ($book === null) {
            return ["errors" => ["_form" => "Le livre est introuvable"]];
        }

        $title = trim($post["title"] ?? "");
        $author = trim($post["author"] ?? "");
        $description = trim($post["description"] ?? "");
        $publishAt = trim($post["publish_at"] ?? "");
        $categoryIds = $post["categories"] ?? [];
        $errors = [];

        if ($title === "") {
            $errors["title"] = "Le titre est obligatoire";
        }
        if ($author === "") {
            $errors["author"] = "L'auteur est obligatoire";
        }
        if ($description === "") {
            $errors["description"] = "La description est obligatoire";
        }
        if ($publishAt === "") {
            $errors["publish_at"] = "La date de publication est obligatoire";
        }

        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setDescription($description);
        $book->setPublishAt(new \DateTime($publishAt));
        $book->setIsAvailable(isset($post["available"]));

        try {
            $cover = $this->uploadService->uploadFile($files["cover"] ?? []);
            $book->setCover($cover);
        } catch (UploadException $e) {
            if (($files["cover"]["tmp_name"] ?? "") !== "") {
                return ["errors" => ["cover" => $e->getMessage()]];
            }
        }

        $this->resetCategories($book);

        if (is_array($categoryIds)) {
            foreach ($categoryIds as $categoryId) {
                $category = $this->categoryRepository->find((int) $categoryId);

                if ($category !== null) {
                    $book->addCategory($category);
                }
            }
        }

        if (!$this->bookRepository->update($book)) {
            return ["errors" => ["_form" => "Erreur lors de la modification du livre"]];
        }

        return [
            "message" => "Le livre a ete modifie en BDD",
            "book" => $book,
        ];
    }

    public function deleteBook(int $id): bool
    {
        return $this->bookRepository->delete($id);
    }

    public function getBookById(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function getAllBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    private function resetCategories(Book $book): void
    {
        foreach ($book->getCategories() as $category) {
            $book->removeCategory($category);
        }
    }
}
