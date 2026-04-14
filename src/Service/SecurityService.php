<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class SecurityService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function saveUser(array $post): array
    {
        $firstname = trim($post["firstname"] ?? "");
        $lastname = trim($post["lastname"] ?? "");
        $email = trim($post["email"] ?? "");
        $password = trim($post["password"] ?? "");
        $confirmPassword = trim($post["confirm-password"] ?? "");
        $errors = [];

        if ($firstname === "") {
            $errors["firstname"] = "Le prenom est obligatoire";
        }
        if ($lastname === "") {
            $errors["lastname"] = "Le nom est obligatoire";
        }
        if ($email === "") {
            $errors["email"] = "L'email est obligatoire";
        }
        if ($password === "") {
            $errors["password"] = "Le mot de passe est obligatoire";
        }
        if ($confirmPassword === "") {
            $errors["confirm-password"] = "La confirmation est obligatoire";
        }
        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["errors" => ["email" => "L'email est invalide"]];
        }

        if ($password !== $confirmPassword) {
            return ["errors" => ["confirm-password" => "Les mots de passe ne sont pas identiques"]];
        }

        if ($this->userRepository->isUserExists($email)) {
            return ["errors" => ["_form" => "Le compte existe deja en BDD"]];
        }

        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setRoles("ROLE_USER");

        $savedUser = $this->userRepository->create($user);

        if ($savedUser->getId() === 0) {
            return ["errors" => ["_form" => "Erreur lors de la creation du compte"]];
        }

        return ["message" => "Le compte a ete ajoute en BDD"];
    }

    public function authenticate(array $post): array
    {
        $email = trim($post["email"] ?? "");
        $password = trim($post["password"] ?? "");
        $errors = [];

        if ($email === "") {
            $errors["email"] = "L'email est obligatoire";
        }
        if ($password === "") {
            $errors["password"] = "Le mot de passe est obligatoire";
        }
        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user === null || !password_verify($password, $user->getPassword())) {
            return ["errors" => ["_form" => "Les informations de connexion sont invalides"]];
        }

        $_SESSION["user"] = [
            "id" => $user->getId(),
            "firstname" => $user->getFirstname(),
            "lastname" => $user->getLastname(),
            "email" => $user->getEmail(),
            "roles" => explode(',', $user->getRoles()),
        ];

        return ["message" => "Connecte"];
    }

    public function getProfil(): array
    {
        if (!isset($_SESSION["user"])) {
            throw new \Exception("Le profil n'existe pas");
        }

        return $_SESSION["user"];
    }

    public function updateProfil(array $post, int $id): array
    {
        $user = $this->userRepository->find($id);

        if ($user === null) {
            return ["errors" => ["_form" => "Le profil est introuvable"]];
        }

        $firstname = trim($post["firstname"] ?? "");
        $lastname = trim($post["lastname"] ?? "");
        $email = trim($post["email"] ?? "");
        $password = trim($post["password"] ?? "");
        $confirmPassword = trim($post["confirm-password"] ?? "");
        $errors = [];

        if ($firstname === "") {
            $errors["firstname"] = "Le prenom est obligatoire";
        }
        if ($lastname === "") {
            $errors["lastname"] = "Le nom est obligatoire";
        }
        if ($email === "") {
            $errors["email"] = "L'email est obligatoire";
        }
        if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "L'email est invalide";
        }
        if ($password !== "" && $confirmPassword === "") {
            $errors["confirm-password"] = "La confirmation est obligatoire";
        }
        if ($password !== "" && $password !== $confirmPassword) {
            $errors["confirm-password"] = "Les mots de passe ne sont pas identiques";
        }
        if ($this->userRepository->isUserExistsForAnotherUser($email, $id)) {
            $errors["email"] = "L'email est deja utilise";
        }
        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);

        if ($password !== "") {
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        }

        if (!$this->userRepository->update($user)) {
            return ["errors" => ["_form" => "Erreur lors de la mise a jour du profil"]];
        }

        $_SESSION["user"] = [
            "id" => $user->getId(),
            "firstname" => $user->getFirstname(),
            "lastname" => $user->getLastname(),
            "email" => $user->getEmail(),
            "roles" => explode(',', $user->getRoles()),
        ];

        return [
            "message" => "Le profil a ete mis a jour",
            "user" => $_SESSION["user"],
        ];
    }
}
