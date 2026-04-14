<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Lending;
use App\Entity\User;

class LendingRepository extends AbstractRepository
{
    public function findAll(): array
    {
        try {
            $sql = 'SELECT l.id, l.lend_at, l.return_at, l.mandatory_at, l.book_id, l.user_id
            FROM lending AS l
            ORDER BY l.lend_at DESC';
            $req = $this->connect->prepare($sql);
            $req->execute();
            $lendings = [];

            foreach ($req->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $lendings[] = $this->hydrate($row);
            }
        } catch (\PDOException $e) {
            return [];
        }

        return $lendings;
    }

    public function find(int $id): ?Lending
    {
        try {
            $sql = 'SELECT l.id, l.lend_at, l.return_at, l.mandatory_at, l.book_id, l.user_id
            FROM lending AS l
            WHERE l.id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);
            $req->execute();
            $lending = $req->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return null;
        }

        return $lending ? $this->hydrate($lending) : null;
    }

    public function create(Lending $lending): Lending
    {
        try {
            $sql = 'INSERT INTO lending(lend_at, return_at, mandatory_at, book_id, user_id)
            VALUE(?,?,?,?,?)';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $lending->getLendAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $req->bindValue(2, $lending->getReturnAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $req->bindValue(3, $lending->getMandatoryAt()->format('Y-m-d'), \PDO::PARAM_STR);
            $req->bindValue(4, $lending->getBook()->getId(), \PDO::PARAM_INT);
            $req->bindValue(5, $lending->getUser()->getId(), \PDO::PARAM_INT);
            $req->execute();

            $lending->setId((int) $this->connect->lastInsertId());
        } catch (\PDOException $e) {
            return $lending;
        }

        return $lending;
    }

    public function update(Lending $lending): bool
    {
        try {
            $sql = 'UPDATE lending
            SET lend_at = ?, return_at = ?, mandatory_at = ?, book_id = ?, user_id = ?
            WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $lending->getLendAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $req->bindValue(2, $lending->getReturnAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $req->bindValue(3, $lending->getMandatoryAt()->format('Y-m-d'), \PDO::PARAM_STR);
            $req->bindValue(4, $lending->getBook()->getId(), \PDO::PARAM_INT);
            $req->bindValue(5, $lending->getUser()->getId(), \PDO::PARAM_INT);
            $req->bindValue(6, $lending->getId(), \PDO::PARAM_INT);

            return $req->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = 'DELETE FROM lending WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);

            return $req->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    private function hydrate(array $data): Lending
    {
        $lending = new Lending();
        $lending->setId((int) $data['id']);
        $lending->setLendAt(new \DateTime($data['lend_at']));
        $lending->setReturnAt(new \DateTime($data['return_at']));
        $lending->setMandatoryAt(new \DateTime($data['mandatory_at']));
        $lending->setBook($this->hydrateBook((int) $data['book_id']));
        $lending->setUser($this->hydrateUser((int) $data['user_id']));

        return $lending;
    }

    private function hydrateBook(int $bookId): Book
    {
        $sql = 'SELECT b.id, b.title, b.description, b.publish_at, b.cover, b.author, b.available
        FROM book AS b
        WHERE b.id = ?';
        $req = $this->connect->prepare($sql);
        $req->bindValue(1, $bookId, \PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch(\PDO::FETCH_ASSOC);

        $book = new Book();
        $book->setId((int) $data['id']);
        $book->setTitle($data['title']);
        $book->setDescription($data['description']);
        $book->setPublishAt(new \DateTime($data['publish_at']));
        $book->setCover((string) ($data['cover'] ?? ''));
        $book->setAuthor($data['author']);
        $book->setIsAvailable((bool) $data['available']);

        return $book;
    }

    private function hydrateUser(int $userId): User
    {
        $sql = 'SELECT u.id, u.firstname, u.lastname, u.email, u.password, u.roles
        FROM users AS u
        WHERE u.id = ?';
        $req = $this->connect->prepare($sql);
        $req->bindValue(1, $userId, \PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch(\PDO::FETCH_ASSOC);

        $user = new User();
        $user->setId((int) $data['id']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles($data['roles']);

        return $user;
    }
}
