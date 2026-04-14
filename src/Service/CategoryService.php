<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function saveCategory(array $post): array
    {
        $name = trim($post["name"] ?? "");

        if ($name === "") {
            return ["errors" => ["name" => "Le nom de la categorie est obligatoire"]];
        }

        $category = new Category();
        $category->setName($name);
        $savedCategory = $this->categoryRepository->create($category);

        if ($savedCategory->getId() === 0) {
            return ["errors" => ["_form" => "Erreur lors de l'ajout de la categorie"]];
        }

        return [
            "message" => "La categorie a ete ajoutee en BDD",
            "category" => $savedCategory,
        ];
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }
}
