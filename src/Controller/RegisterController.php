<?php

namespace App\Controller;

use App\Service\SecurityService;

class RegisterController extends AbstractController
{
    private SecurityService $securityService;

    public function __construct()
    {
        $this->securityService = new SecurityService();
    }

    public function register(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();

        if ($this->isFormSubmitted($_POST, "submit")) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("register", "S'inscrire", $data);
            }

            $result = $this->securityService->saveUser($_POST);
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";
        }

        return $this->render("register", "S'inscrire", $data);
    }

    public function login(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();

        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("login", "Se connecter", $data);
            }

            $result = $this->securityService->authenticate($_POST);
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";

            if (!empty($data["msg"])) {
                header("Location: /profil");
                exit;
            }
        }

        return $this->render("login", "Se connecter", $data);
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        header("Location: /");
        exit;
    }

    public function showProfil(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();

        try {
            $data["user"] = $this->securityService->getProfil();
        } catch (\Exception $e) {
            $this->logout();
        }

        $data["form"] = $data["user"];

        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("profil", "Profil utilisateur", $data);
            }

            $result = $this->securityService->updateProfil($_POST, (int) ($data["user"]["id"] ?? 0));
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";
            $data["user"] = $result["user"] ?? $data["user"];
            $data["form"] = array_merge($data["user"], $_POST);
        }

        return $this->render("profil", "Profil utilisateur", $data);
    }
}
