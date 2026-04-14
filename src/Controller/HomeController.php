<?php

namespace App\Controller;

use App\Service\BookService;
use App\Service\CategoryService;
use App\Service\LendingService;
use App\Service\UserService;

class HomeController extends AbstractController
{
    private CategoryService $categoryService;
    private BookService $bookService;
    private UserService $userService;
    private LendingService $lendingService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->bookService = new BookService();
        $this->userService = new UserService();
        $this->lendingService = new LendingService();
    }

    public function index(): mixed
    {
        $data = [];
        $data["categoriesCount"] = count($this->categoryService->getAllCategories());
        $data["booksCount"] = count($this->bookService->getAllBooks());
        $data["usersCount"] = count($this->userService->getAllUsers());
        $data["lendingsCount"] = count($this->lendingService->getAllLendings());

        return $this->render("home", "Accueil", $data);
    }
}
