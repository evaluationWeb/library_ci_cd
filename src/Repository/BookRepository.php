<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;

class BookRepository extends AbstractRepository
{
    public function findAll(): array
    {
        try {
            $books = $this->findBooksByAvailability();
        } catch (\PDOException $e) {
            return [];
        }

        return array_values($books);
    }

    public function findAvailable(): array
    {
        try {
            $books = $this->findBooksByAvailability(true);
        } catch (\PDOException $e) {
            return [];
        }

        return array_values($books);
    }

    public function find(int $id): ?Book
    {
        try {
            $sql = 'SELECT b.id, b.title, b.description, b.publish_at, b.cover, b.author, b.available
            FROM book AS b
            WHERE b.id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);
            $req->execute();
            $book = $req->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return null;
        }

        return $book ? $this->hydrate($book, true) : null;
    }

    public function create(Book $book): Book
    {
        try {
            $sql = 'INSERT INTO book(title, description, publish_at, cover, author, available)
            VALUE(?,?,?,?,?,?)';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $book->getTitle(), \PDO::PARAM_STR);
            $req->bindValue(2, $book->getDescription(), \PDO::PARAM_STR);
            $req->bindValue(3, $book->getPublishAt()->format('Y-m-d'), \PDO::PARAM_STR);
            $req->bindValue(4, $book->getCover(), \PDO::PARAM_STR);
            $req->bindValue(5, $book->getAuthor(), \PDO::PARAM_STR);
            $req->bindValue(6, $book->isAvailable() ? 1 : 0, \PDO::PARAM_INT);
            $req->execute();

            $book->setId((int) $this->connect->lastInsertId());
            $this->syncCategories($book);
        } catch (\PDOException $e) {
            return $book;
        }

        return $book;
    }

    public function update(Book $book): bool
    {
        try {
            $sql = 'UPDATE book
            SET title = ?, description = ?, publish_at = ?, cover = ?, author = ?, available = ?
            WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $book->getTitle(), \PDO::PARAM_STR);
            $req->bindValue(2, $book->getDescription(), \PDO::PARAM_STR);
            $req->bindValue(3, $book->getPublishAt()->format('Y-m-d'), \PDO::PARAM_STR);
            $req->bindValue(4, $book->getCover(), \PDO::PARAM_STR);
            $req->bindValue(5, $book->getAuthor(), \PDO::PARAM_STR);
            $req->bindValue(6, $book->isAvailable() ? 1 : 0, \PDO::PARAM_INT);
            $req->bindValue(7, $book->getId(), \PDO::PARAM_INT);
            $updated = $req->execute();

            if ($updated) {
                $this->syncCategories($book);
            }
        } catch (\PDOException $e) {
            return false;
        }

        return $updated;
    }

    public function delete(int $id): bool
    {
        try {
            $sql = 'DELETE FROM book WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);

            return $req->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function updateAvailability(int $id, bool $available): bool
    {
        try {
            $sql = 'UPDATE book SET available = ? WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $available ? 1 : 0, \PDO::PARAM_INT);
            $req->bindValue(2, $id, \PDO::PARAM_INT);

            return $req->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    private function hydrate(array $data, bool $loadCategories = false): Book
    {
        $book = new Book();
        $book->setId((int) $data['id']);
        $book->setTitle($data['title']);
        $book->setDescription($data['description']);
        $book->setPublishAt(new \DateTime($data['publish_at']));
        $book->setCover((string) ($data['cover'] ?? ''));
        $book->setAuthor($data['author']);
        $book->setIsAvailable((bool) $data['available']);

        if ($loadCategories) {
            foreach ($this->findCategoriesByBookId($book->getId()) as $category) {
                $book->addCategory($category);
            }
        }

        return $book;
    }

    private function findCategoriesByBookId(int $bookId): array
    {
        try {
            $sql = 'SELECT c.id, c.name
            FROM category AS c
            INNER JOIN book_category AS bc ON bc.category_id = c.id
            WHERE bc.book_id = ?
            ORDER BY c.name ASC';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $bookId, \PDO::PARAM_INT);
            $req->execute();
            $categories = [];

            foreach ($req->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $category = new Category();
                $category->setId((int) $row['id']);
                $category->setName($row['name']);
                $categories[] = $category;
            }
        } catch (\PDOException $e) {
            return [];
        }

        return $categories;
    }

    private function syncCategories(Book $book): void
    {
        try {
            $sql = 'DELETE FROM book_category WHERE book_id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $book->getId(), \PDO::PARAM_INT);
            $req->execute();

            if ($book->getCategories() === []) {
                return;
            }

            foreach ($book->getCategories() as $category) {
                $sql = 'INSERT INTO book_category(book_id, category_id) VALUE(?,?)';
                $req = $this->connect->prepare($sql);
                $req->bindValue(1, $book->getId(), \PDO::PARAM_INT);
                $req->bindValue(2, $category->getId(), \PDO::PARAM_INT);
                $req->execute();
            }
        } catch (\PDOException $e) {
        }
    }

    private function findBooksByAvailability(?bool $available = null): array
    {
        $sql = 'SELECT
                    b.id,
                    b.title,
                    b.description,
                    b.publish_at,
                    b.cover,
                    b.author,
                    b.available,
                    c.id AS category_id,
                    c.name AS category_name
                FROM book AS b
                LEFT JOIN book_category AS bc ON bc.book_id = b.id
                LEFT JOIN category AS c ON c.id = bc.category_id';

        if ($available !== null) {
            $sql .= ' WHERE b.available = ?';
        }

        $sql .= ' ORDER BY b.title ASC, c.name ASC';

        $req = $this->connect->prepare($sql);

        if ($available !== null) {
            $req->bindValue(1, $available ? 1 : 0, \PDO::PARAM_INT);
        }

        $req->execute();
        $books = [];

        foreach ($req->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $bookId = (int) $row['id'];

            if (!isset($books[$bookId])) {
                $books[$bookId] = $this->hydrate($row, false);
            }

            if ($row['category_id'] !== null) {
                $category = new Category();
                $category->setId((int) $row['category_id']);
                $category->setName($row['category_name']);
                $books[$bookId]->addCategory($category);
            }
        }

        return $books;
    }
}
