<?php

declare(strict_types=1);

class GenerateController
{
    private string $storageFile;

    public function __construct()
    {
        $this->storageFile = __DIR__ . '/../../storage/usage.json';
    }

    public function generate(): void
    {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $context     = trim($body['context'] ?? '');
        $objective   = trim($body['objective'] ?? '');
        $description = trim($body['description'] ?? '');
        $personType  = trim($body['person_type'] ?? '');
        $userType    = $body['user_type'] ?? 'free';

        if ($context === '' || $objective === '') {
            http_response_code(400);
            echo json_encode(['error' => 'context and objective are required']);
            return;
        }

        $ip = $this->getClientIp();

        if ($userType === 'free' && !$this->checkLimit($ip)) {
            http_response_code(429);
            echo json_encode(['error' => 'limit_reached']);
            return;
        }

        $service = new GenerateExcuseService();
        $result  = $service->generate($context, $objective, $description, $personType);

        if (isset($result['error'])) {
            http_response_code(500);
            echo json_encode($result);
            return;
        }

        if ($userType === 'free') {
            $this->incrementUsage($ip);
        }

        echo json_encode($result);
    }

    private function getClientIp(): string
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function ipToKey(string $ip): string
    {
        // Normaliza IPv4 e IPv6 a un key seguro para JSON
        return 'ip_' . preg_replace('/[^a-zA-Z0-9]/', '_', $ip);
    }

    private function checkLimit(string $ip): bool
    {
        $usage = $this->loadUsage();
        $key   = $this->ipToKey($ip);
        $today = date('Y-m-d');

        if (!isset($usage[$key])) {
            return true;
        }
        if ($usage[$key]['date'] !== $today) {
            return true;
        }
        return $usage[$key]['count'] < 3;
    }

    private function incrementUsage(string $ip): void
    {
        $usage = $this->loadUsage();
        $key   = $this->ipToKey($ip);
        $today = date('Y-m-d');

        if (!isset($usage[$key]) || $usage[$key]['date'] !== $today) {
            $usage[$key] = ['date' => $today, 'count' => 1];
        } else {
            $usage[$key]['count']++;
        }

        file_put_contents(
            $this->storageFile,
            json_encode($usage, JSON_PRETTY_PRINT),
            LOCK_EX
        );
    }

    private function loadUsage(): array
    {
        if (!file_exists($this->storageFile)) {
            return [];
        }
        $content = file_get_contents($this->storageFile);
        return json_decode($content, true) ?? [];
    }
}
