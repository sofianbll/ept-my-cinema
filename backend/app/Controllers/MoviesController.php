<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\ValidationHelper;
use App\Models\Movies;
use JetBrains\PhpStorm\NoReturn;

class MoviesController extends Controller
{

    #[NoReturn]
    public function list(): void // GET //
    {
        $this->jsonResponse(Movies::all());
    }

    #[NoReturn]
    public function show($id): void // GET //
    {
        $movie = $this->findOrFail(Movies::class, $id, 'Movie not found');

        $response = $movie->jsonSerialize();
        $response['screenings'] = $movie->getScreenings();
        $response['rooms']      = $movie->getRooms();

        $this->jsonResponse($response);
    }

    #[NoReturn]
    public function create(): void
    {
        $data = $this->getJsonBody();

        $missing = ValidationHelper::required($data, ['title', 'duration', 'release_year']);
        if (!empty($missing)) {
            $this->error('Missing required fields', 400, ['fields' => $missing]);
        }

        $movie = new Movies();
        $movie->setTitle($data['title']);
        $movie->setDescription($data['description'] ?? null);
        $movie->setDirector($data['director'] ?? null);
        $movie->setDuration($data['duration']);
        $movie->setReleaseYear($data['release_year']);
        $movie->setGenre($data['genre'] ?? null);
        $movie->save();

        $this->jsonResponse('Movie created', 201);
    }

    #[NoReturn]
    public function update($id): void // PUT //
    {
        $movie = $this->findOrFail(Movies::class, $id, 'Movie not found');

        $data = $this->getJsonBody();


        $movie->setTitle($data['title'] ?? $movie->getTitle());
        $movie->setDescription($data['description'] ?? $movie->getDescription());
        $movie->setDirector($data['director'] ?? $movie->getDirector());
        $movie->setDuration($data['duration'] ?? $movie->getDuration());
        $movie->setReleaseYear($data['release_year'] ?? $movie->getReleaseYear());
        $movie->setGenre($data['genre'] ?? $movie->getGenre());
        $movie->save();

        $this->jsonResponse('Movie updated');
    }

    #[NoReturn]
    public function delete($id): void // DELETE //
    {
        $movie = $this->findOrFail(Movies::class, $id, 'Movie not found');

        if ($movie->hasScreenings()) {
            $this->error('Cannot delete movie with existing screenings', 409);
        }

        $movie->delete();
        $this->jsonResponse('Movie deleted');
    }


}