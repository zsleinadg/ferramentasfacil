<?php

class Cloudinary
{
    private static function config(): array
    {
        return require basePath('config/cloudinary.php');
    }

    public static function upload(string $filePath, ?string $publicId = null, ?string $folder = null): ?array
    {
        $config = self::config();
        $endpoint = "https://api.cloudinary.com/v1_1/{$config['cloud_name']}/image/upload";

        $signParams = ['timestamp' => time()];
        if ($publicId) {
            $signParams['public_id'] = $publicId;
        }
        if ($folder) {
            $signParams['folder'] = $folder;
        }

        $params = $signParams;
        $params['api_key'] = $config['api_key'];
        $params['signature'] = self::sign($signParams, $config['api_secret']);
        $params['file'] = new CURLFile($filePath);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new RuntimeException("Erro de conexão com Cloudinary: {$error}");
        }

        $data = json_decode($response, true);

        if ($httpCode !== 200 || !$data || isset($data['error'])) {
            $msg = $data['error']['message'] ?? 'Erro desconhecido no Cloudinary';
            throw new RuntimeException("Falha no upload: {$msg}");
        }

        return $data;
    }

    public static function delete(string $publicId): bool
    {
        $config = self::config();
        $endpoint = "https://api.cloudinary.com/v1_1/{$config['cloud_name']}/image/destroy";

        $signParams = [
            'timestamp' => time(),
            'public_id' => $publicId,
        ];

        $params = $signParams;
        $params['api_key'] = $config['api_key'];
        $params['signature'] = self::sign($signParams, $config['api_secret']);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);
        return $httpCode === 200 && ($data['result'] ?? '') === 'ok';
    }

    public static function extractPublicId(string $url): ?string
    {
        if (preg_match('#/v\d+/(?:upload/)?(.+?)(?:\.\w+)?$#', $url, $m)) {
            $id = $m[1];
            $id = preg_replace('/\.[^.]+$/', '', $id);
            return $id;
        }
        return null;
    }

    private static function sign(array $params, string $secret): string
    {
        ksort($params);
        $parts = [];
        foreach ($params as $key => $value) {
            $parts[] = "{$key}={$value}";
        }
        return sha1(implode('&', $parts) . $secret);
    }
}
