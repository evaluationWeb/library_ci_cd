<?php

namespace App\Controller;

use App\Service\UserService;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function addUser(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();

        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("add_user", "Ajouter un utilisateur", $data);
            }

            $result = $this->userService->saveUser($_POST);
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";
        }

        return $this->render("add_user", "Ajouter un utilisateur", $data);
    }

    public function showAllUsers(): mixed
    {
        $data = [];
        $data["users"] = $this->userService->getAllUsers();

        return $this->render("users", "Liste des utilisateurs", $data);
    }
}
