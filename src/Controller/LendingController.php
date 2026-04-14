<?php

namespace App\Controller;

use App\Service\LendingService;

class LendingController extends AbstractController
{
    private LendingService $lendingService;

    public function __construct()
    {
        $this->lendingService = new LendingService();
    }

    public function addLending(): mixed
    {
        $data = [];
        $data["csrf_token"] = $this->getCsrfToken();
        $data["books"] = $this->lendingService->getAvailableBooks();
        $data["form"] = $_POST;

        if (isset($_GET["book_id"])) {
            $data["form"]["book_id"] = $_GET["book_id"];
        }

        if ($this->isFormSubmitted($_POST)) {
            if (!$this->isCsrfTokenValid($_POST)) {
                $data["errors"]["_form"] = "Token CSRF invalide";
                return $this->render("add_lending", "Ajouter un emprunt", $data);
            }

            $result = $this->lendingService->saveLending($_POST, (int) ($_SESSION["user"]["id"] ?? 0));
            $data["errors"] = $result["errors"] ?? [];
            $data["msg"] = $result["message"] ?? "";
            $data["books"] = $this->lendingService->getAvailableBooks();
        }

        return $this->render("add_lending", "Ajouter un emprunt", $data);
    }

    public function showAllLendings(): mixed
    {
        $data = [];
        $data["lendings"] = $this->lendingService->getAllLendings();

        return $this->render("lendings", "Liste des emprunts", $data);
    }

    public function returnLending(int $id): void
    {
        $this->lendingService->returnLending($id);
        header("Location: /lending/all");
        exit;
    }
}
