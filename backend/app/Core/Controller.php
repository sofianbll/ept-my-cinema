<?php

namespace App\Core;

use JetBrains\PhpStorm\NoReturn;

abstract class Controller
{
    #[NoReturn]
    protected function jsonResponse(mixed $data, int $status = 200): void
    {
        $allowOrigin = $_ENV['ALLOWED_ORIGIN'];

        http_response_code($status);
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: $allowOrigin");
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        echo json_encode($data);
        exit();
    }

    protected function getJsonBody(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }



    #[NoReturn]
    protected function error(string $message, int $status = 400, array $data = []): void
    {
        $response = ['error' => $message];
        $response = array_merge($response, $data);
        $this->jsonResponse($response,$status);
    }
    protected function findOrFail(string $modelClass, int $id, string $message = 'Resource not found'): mixed
    {
        $entity = $modelClass::find($id); // Retrieve ID from Class Methods
        if (!$entity) {
            $this->error($message, 404);
        }
        return $entity;
    }

}