<?php

declare(strict_types=1);

class Request
{
    public function __construct(
        private string $method,
        private string $path,
        private array $query = [],
        private array $body = [],
        private array $session = []
    ) {}

    public static function fromGlobals(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        return new self(
            strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')),
            $path,
            is_array($_GET) ? $_GET : [],
            is_array($_POST) ? $_POST : [],
            isset($_SESSION) && is_array($_SESSION) ? $_SESSION : []
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function hasBody(): bool
    {
        return $this->body !== [];
    }

    public function query(string $key, string $default = ''): string
    {
        $value = $this->query[$key] ?? $default;

        return is_string($value) ? $value : $default;
    }

    public function input(string $key, string $default = ''): string
    {
        $value = $this->body[$key] ?? $default;

        return is_string($value) ? $value : $default;
    }

    public function session(string $key, mixed $default = null): mixed
    {
        return $this->session[$key] ?? $default;
    }
}
