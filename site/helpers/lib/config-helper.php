<?php

class configHelper
{
    public static function setEnvURL(string $port, bool $isSecure): ?string
    {
        $protocol = $isSecure ? "https://" : "http://";
        return match ($_ENV['VITE_ENV_MODE'] ?? 'dev') {
            'dev'  => $protocol . 'localhost' . $port,
            'host' => $protocol . $_ENV['VITE_LOCAL_IP'] . $port ?? null,
            default => null
        };
    }

    public static function setDebugMode(): bool
    {
        return in_array($_ENV['VITE_ENV_MODE'] ?? '', ['dev', 'host'], true);
    }
}
