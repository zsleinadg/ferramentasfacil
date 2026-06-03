<?php

class Upload
{
    private static array $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    private static int $maxSize = 5 * 1024 * 1024;

    public static function image(array $file, string $subdir = 'tools'): ?string
    {
        $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($error !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmpName = $file['tmp_name'];
        $size = $file['size'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        if (!in_array($mimeType, self::$allowedMimes)) {
            throw new RuntimeException('Tipo de arquivo não permitido. Aceito: JPG, PNG, WEBP.');
        }

        if ($size > self::$maxSize) {
            throw new RuntimeException('Arquivo muito grande. Máximo de 5MB.');
        }

        $publicId = uniqid() . '_' . time();
        $folder = 'ferramentasfacil/' . trim($subdir, '/');

        $result = Cloudinary::upload($tmpName, $publicId, $folder);

        return $result['secure_url'] ?? $result['url'] ?? null;
    }

    public static function deleteImage(string $url): bool
    {
        if (empty($url)) return false;

        $publicId = Cloudinary::extractPublicId($url);
        if ($publicId) {
            return Cloudinary::delete($publicId);
        }

        $fullPath = basePath('public/' . ltrim($url, '/'));
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    public static function deleteGallery(int $toolId): void
    {
        $toolImageModel = new ToolImage();
        $images = $toolImageModel->getByToolId($toolId);
        foreach ($images as $img) {
            self::deleteImage($img['imageurl']);
        }
        $toolImageModel->deleteByToolId($toolId);
    }
}
