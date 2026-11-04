<?php

namespace App;

use App\Core\ErrorHandler;
use App\Core\Router;
use App\Controllers\HomeController;
use App\Models\Movies;
use Dotenv\Dotenv;

class App
{
    protected string $controller = 'home';
    protected string $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        // Loading the .env var
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // Set Error handler
        set_exception_handler([ ErrorHandler::class, 'handle' ]);
    }

    public function run(): void
    {
        new Router();
    }
}

