<?php

declare(strict_types=1);

namespace App\Services;

class MailService
{
    private string $host;
    private int $port;
    private string $user;
    private string $pass;
    private string $from;

    public function __construct()
    {
        $this->host = getenv('MAIL_HOST') ?: 'mailpit';
        $this->port = (int) (getenv('MAIL_PORT') ?: 1025);
        $this->user = getenv('MAIL_USER') ?: '';
        $this->pass = getenv('MAIL_PASS') ?: '';
        $this->from = getenv('MAIL_FROM') ?: 'noreply@example.com';
    }

    public function send(string $to, string $subject, string $body): bool
    {
        $socket = @fsockopen($this->host, $this->port, $errno, $errstr, 10);
        if (!$socket) {
            return false;
        }

        $this->read($socket);

        $this->write($socket, 'EHLO localhost');
        $this->read($socket);

        if ($this->user !== '' && $this->pass !== '') {
            $this->write($socket, 'AUTH LOGIN');
            $this->read($socket);

            $this->write($socket, base64_encode($this->user));
            $this->read($socket);

            $this->write($socket, base64_encode($this->pass));
            $response = $this->read($socket);

            if (!str_starts_with($response, '235')) {
                fclose($socket);
                return false;
            }
        }

        $this->write($socket, 'MAIL FROM:<' . $this->from . '>');
        $this->read($socket);

        $this->write($socket, 'RCPT TO:<' . $to . '>');
        $this->read($socket);

        $this->write($socket, 'DATA');
        $this->read($socket);

        $headers = "From: {$this->from}\r\n";
        $headers .= "To: {$to}\r\n";
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $this->write($socket, $headers . "\r\n" . $body . "\r\n.");
        $this->read($socket);

        $this->write($socket, 'QUIT');
        fclose($socket);

        return true;
    }

    private function write($socket, string $data): void
    {
        fwrite($socket, $data . "\r\n");
    }

    private function read($socket): string
    {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (!isset($line[3]) || $line[3] === ' ') {
                break;
            }
        }
        return $response;
    }
}
