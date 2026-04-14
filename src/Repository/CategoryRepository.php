<?php

namespace App\Repository;

use App\Entity\Category;

class CategoryRepository extends AbstractRepository
{
    public function findAll(): array
    {
        try {
            $sql = 'SELECT c.id, c.name FROM category AS c ORDER BY c.name ASC';
            $req = $this->connect->prepare($sql);
            $req->execute();
            $categories = [];

            foreach ($req->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $categories[] = $this->hydrate($row);
            }
        } catch (\PDOException $e) {
            return [];
        }

        return $categories;
    }

    public function find(int $id): ?Category
    {
        try {
            $sql = 'SELECT c.id, c.name FROM category AS c WHERE c.id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);
            $req->execute();
            $category = $req->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return null;
        }

        return $category ? $this->hydrate($category) : null;
    }

    public function create(Category $category): Category
    {
        try {
            $sql = 'INSERT INTO category(name) VALUE(?)';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $category->getName(), \PDO::PARAM_STR);
            $req->execute();

            $category->setId((int) $this->connect->lastInsertId());
        } catch (\PDOException $e) {
            return $category;
        }

        return $category;
    }

    public function update(Category $category): bool
    {
        try {
            $sql = 'UPDATE category SET name = ? WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $category->getName(), \PDO::PARAM_STR);
            $req->bindValue(2, $category->getId(), \PDO::PARAM_INT);

            return $req->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = 'DELETE FROM category WHERE id = ?';
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);

            return $req->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    private function hydrate(array $data): Category
    {
        $category = new Category();
        $category->setId((int) $data['id']);
        $category->setName($data['name']);

        return $category;
    }
}
