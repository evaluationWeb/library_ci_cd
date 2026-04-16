<?php

namespace App\Service;

use App\Service\Exception\UploadException;

class UploadService
{
    private readonly string $uploadTarget;
    private readonly string $uploadPublicPath;
    private readonly int $uploadSizeMax;
    private readonly string $uploadFormatWhiteList;
    private array $uploadFormats;

    public function __construct()
    {
        $projectRoot = dirname(__DIR__, 2);
        $uploadDirectory = $_ENV["UPLOAD_DIRECTORY"] ?? "public/assets/uploads";

        $this->uploadTarget = rtrim($projectRoot . DIRECTORY_SEPARATOR . trim($uploadDirectory, "/\\"), "/\\")
            . DIRECTORY_SEPARATOR;
        $this->uploadPublicPath = rtrim($_ENV["UPLOAD_PUBLIC_PATH"] ?? "/assets/uploads", "/");
        $this->uploadSizeMax = (int) ($_ENV["UPLOAD_SIZE_MAX"] ?? 2097152);
        $this->uploadFormatWhiteList = $_ENV["UPLOAD_FORMAT_WHITE_LIST"] ?? '["png","jpg","jpeg","webp"]';
        $this->uploadFormats = json_decode($this->uploadFormatWhiteList, true) ?? [];
    }

    public function uploadFile(array $files): string
    {
        if ($this->isFileMissing($files)) {
            throw new UploadException("Pas de fichier a importer");
        }

        if (!isset($files["error"]) || $files["error"] !== UPLOAD_ERR_OK) {
            throw new UploadException("Erreur lors de l'upload du fichier");
        }

        if (!is_uploaded_file($files["tmp_name"])) {
            throw new UploadException("Fichier uploade invalide");
        }

        if ($this->isUploadTooLarge($files)) {
            throw new UploadException("La taille du fichier est trop importante");
        }

        $ext = $this->getFileExtension($files["name"] ?? "");

        if (!$this->validateUploadFormat($ext)) {
            throw new UploadException("Le format " . $ext . " est invalide");
        }

        if (!is_dir($this->uploadTarget) && !mkdir($this->uploadTarget, 0755, true) && !is_dir($this->uploadTarget)) {
            throw new UploadException("Dossier d'upload introuvable ou non inscriptible");
        }

        if (!is_writable($this->uploadTarget)) {
            throw new UploadException("Dossier d'upload introuvable ou non inscriptible");
        }
        if (!$this->validateMimeType($files["tmp_name"])) {
            throw new UploadException("Type MIME invalide");
        }

        if (!$this->validateImageContent($files["tmp_name"])) {
            throw new UploadException("Image invalide");
        }

        $newName = $this->renameFile($ext);
        $uploadTarget = $this->uploadTarget . $newName;

        if (!move_uploaded_file($files["tmp_name"], $uploadTarget)) {
            throw new UploadException("Echec lors du deplacement du fichier");
        }

        return $this->uploadPublicPath . "/" . $newName;
    }

    private function isFileMissing(array $files): bool
    {
        return !isset($files["tmp_name"]) || empty($files["tmp_name"]);
    }

    private function isUploadTooLarge(array $files): bool
    {
        return ($files["size"] ?? 0) > $this->uploadSizeMax;
    }

    private function validateUploadFormat(string $ext): bool
    {
        if (empty($this->uploadFormats)) {
            return false;
        }

        return in_array($ext, $this->uploadFormats, true);
    }

    private function renameFile(string $ext): string
    {
        return uniqid("book_", true) . "." . $ext;
    }

    private function getFileExtension(string $fileName): string
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    private function validateMimeType(string $tmpName): bool
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpName);

        return in_array($mime, [
            'image/png',
            'image/jpeg',
            'image/webp'
        ], true);
    }

    private function validateImageContent(string $tmpName): bool
    {
        return @getimagesize($tmpName) !== false;
    }
}
