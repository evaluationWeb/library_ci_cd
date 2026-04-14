<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
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
        $roles = trim($post["roles"] ?? "ROLE_USER");
        $errors = [];

        if ($firstname === "") {
            $errors["firstname"] = "Le prenom est obligatoire";
        }
        if ($lastname === "") {
            $errors["lastname"] = "Le nom est obligatoire";
        }
        if ($email === "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors["email"] = "L'email est invalide";
        }
        if ($password === "") {
            $errors["password"] = "Le mot de passe est obligatoire";
        }

        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setRoles($roles);

        $savedUser = $this->userRepository->create($user);

        if ($savedUser->getId() === 0) {
            return ["errors" => ["_form" => "Erreur lors de l'ajout de l'utilisateur"]];
        }

        return [
            "message" => "L'utilisateur a ete ajoute en BDD",
            "user" => $savedUser,
        ];
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }
}
