<?php
/**
 * Shared configuration/helpers for the Tretto MVC app.
 * Keeps URLs independent from the project folder name.
 */

function app_base_url(): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $pos = strpos($script, '/MVC/');
    if ($pos !== false) {
        return rtrim(substr($script, 0, $pos), '/');
    }

    $request = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
    $pos = strpos($request, '/MVC/');
    if ($pos !== false) {
        return rtrim(substr($request, 0, $pos), '/');
    }

    return '';
}

function app_url(string $path = ''): string
{
    $base = app_base_url();
    $path = ltrim($path, '/');
    return ($base === '' ? '' : $base) . ($path === '' ? '' : '/' . $path);
}

function redirect_to(string $path): void
{
    header('Location: ' . app_url($path));
    exit;
}

function ensure_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function is_direct_script(string $file): bool
{
    return realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === realpath($file);
}

function json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}
