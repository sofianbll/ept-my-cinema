<?php

namespace App\Core;

use ErrorException;
use JetBrains\PhpStorm\NoReturn;

class ErrorHandler
{


    /**
     * @throws \JsonException
     */
    #[NoReturn]
    public static function handle(\Throwable $e): void
    {
        $code = $e->getCode() ?: 500;

        if ($e instanceof \PDOException) {
            // Erreur SQL
            $code = 500;
        } else {
            $code = $e->getCode() ?: 500;
        }

        // Implements JSON response
        $allowOrigin = $_ENV['ALLOWED_ORIGIN'];

        http_response_code($code);
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: $allowOrigin");


        if ($_ENV['APP_ENV'] === 'dev') {
            echo json_encode([
                'error' => $e->getMessage(),
                'code'  => $code,
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], JSON_THROW_ON_ERROR);
        } else {
            echo json_encode([ 'error' => $code === 404 ? 'Not Found' : 'Internal Server Error' ], JSON_THROW_ON_ERROR);
        }
        exit();
    }

    /**
     * @throws ErrorException
     */
    public static function handleError($errorNb, $errorStr, $errorFile, $errorLine): void
    {
        throw new ErrorException($errorStr, 0, $errorNb, $errorFile, $errorLine);
    }
}