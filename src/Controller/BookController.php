<?php

namespace App\Controller;

use App\Service\BookService;

class BookController extends AbstractController
{
    private BookService $bookService;

    public function __construct()
    {
        $this->bookService = new BookService();
    }

    public function addBook(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();
        $data["categories"] = $this->bookService->getAllCategories();
        $data["form"] = $_POST;
        $data["mode"] = "create";

        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("add_book", "Ajouter un livre", $data);
            }

            $result = $this->bookService->saveBook($_POST, $_FILES);
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";
        }

        return $this->render("add_book", "Ajouter un livre", $data);
    }

    public function editBook(int $id): mixed
    {
        $book = $this->bookService->getBookById($id);

        if ($book === null) {
            http_response_code(404);
            return $this->render("books", "Livre introuvable", ["books" => []]);
        }

        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();
        $data["categories"] = $this->bookService->getAllCategories();
        $data["mode"] = "edit";
        $data["book"] = $book;
        $data["form"] = $this->hydrateBookForm($book);

        if ($this->isFormSubmitted($_POST)) {
            $data["form"] = $_POST;

            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("add_book", "Modifier un livre", $data);
            }

            $result = $this->bookService->updateBook($id, $_POST, $_FILES);
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";

            if (!empty($result["book"])) {
                $data["book"] = $result["book"];
                $data["form"] = $this->hydrateBookForm($result["book"]);
            }
        }

        return $this->render("add_book", "Modifier un livre", $data);
    }

    public function deleteBook(int $id): void
    {
        if (!$this->isCsrfTokenValid($_POST)) {
            http_response_code(403);
            header("Location: /book/all");
            exit;
        }

        $this->bookService->deleteBook($id);
        header("Location: /book/all");
        exit;
    }

    public function showAllBooks(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();
        $data["books"] = $this->bookService->getAllBooks();

        return $this->render("books", "Liste des livres", $data);
    }

    private function hydrateBookForm(\App\Entity\Book $book): array
    {
        $categoryIds = [];

        foreach ($book->getCategories() as $category) {
            $categoryIds[] = (string) $category->getId();
        }

        return [
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "description" => $book->getDescription(),
            "publish_at" => $book->getPublishAt()->format('Y-m-d'),
            "available" => $book->isAvailable() ? "1" : "",
            "categories" => $categoryIds,
        ];
    }
}
