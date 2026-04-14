<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository
{
    public function findAll(): array
    {
        try {
            $sql = 'SELECT u.id, u.firstname, u.lastname, u.email, u.password, u.roles
            FROM users AS u
            ORDER BY u.lastname ASC, u.firstname ASC';
            $req = $this->connect->prepare($sql);
            $req->execute();
            $users = [];

            foreach ($req->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $users[] = $this->hydrate($row);
            }
        } catch (\PDOException $e) {
            return [];
        }

        return $users;
    }

    public function find(int $id): ?User
    {
        try {
            $sql = 'SELECT u.id, u.firstname, u.lastname, u.email, u.password, u.roles
            FROM users AS u
            WHERE u.id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);
            $req->execute();
            $user = $req->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return null;
        }

        return $user ? $this->hydrate($user) : null;
    }

    public function create(User $user): User
    {
        try {
            $sql = 'INSERT INTO users(firstname, lastname, email, password, roles)
            VALUE(?,?,?,?,?)';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $user->getFirstname(), \PDO::PARAM_STR);
            $req->bindValue(2, $user->getLastname(), \PDO::PARAM_STR);
            $req->bindValue(3, $user->getEmail(), \PDO::PARAM_STR);
            $req->bindValue(4, $user->getPassword(), \PDO::PARAM_STR);
            $req->bindValue(5, $user->getRoles(), \PDO::PARAM_STR);
            $req->execute();

            $user->setId((int) $this->connect->lastInsertId());
        } catch (\PDOException $e) {
            return $user;
        }

        return $user;
    }

    public function update(User $user): bool
    {
        try {
            $sql = 'UPDATE users
            SET firstname = ?, lastname = ?, email = ?, password = ?, roles = ?
            WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $user->getFirstname(), \PDO::PARAM_STR);
            $req->bindValue(2, $user->getLastname(), \PDO::PARAM_STR);
            $req->bindValue(3, $user->getEmail(), \PDO::PARAM_STR);
            $req->bindValue(4, $user->getPassword(), \PDO::PARAM_STR);
            $req->bindValue(5, $user->getRoles(), \PDO::PARAM_STR);
            $req->bindValue(6, $user->getId(), \PDO::PARAM_INT);

            return $req->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = 'DELETE FROM users WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);

            return $req->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function isUserExists(string $email): bool
    {
        try {
            $sql = 'SELECT u.id FROM users AS u WHERE u.email = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $email, \PDO::PARAM_STR);
            $req->execute();
            $user = $req->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }

        return !empty($user);
    }

    public function isUserExistsForAnotherUser(string $email, int $id): bool
    {
        try {
            $sql = 'SELECT u.id FROM users AS u WHERE u.email = ? AND u.id != ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $email, \PDO::PARAM_STR);
            $req->bindValue(2, $id, \PDO::PARAM_INT);
            $req->execute();
            $user = $req->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }

        return !empty($user);
    }

    public function findByEmail(string $email): ?User
    {
        try {
            $sql = 'SELECT u.id, u.firstname, u.lastname, u.email, u.password, u.roles
            FROM users AS u
            WHERE u.email = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $email, \PDO::PARAM_STR);
            $req->execute();
            $user = $req->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return null;
        }

        return $user ? $this->hydrate($user) : null;
    }

    private function hydrate(array $data): User
    {
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
