<?php

namespace Source\Http\Controllers\Api\v1;

use Source\Http\Controllers\Api\ApiController;

use Source\Models\Movie;

use Respect\Validation\Validator;
use Source\Support\Csrf;

/**
 * Class MovieController
 * 
 * @package Source\Http\Controllers\Api\v1
 */
class MovieController extends ApiController
{
    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $data
     * 
     * @return void
     */
    public function create(array $data): void
    {
        if (!Validator::keySet(
            Validator::key('url', Validator::notBlank()),
            Validator::key('category', Validator::notBlank(), false),
            Validator::key('csrf_token', Validator::notBlank(), false)
        )->validate($data)) {
            $this->call(400)->back();
            return;
        }

        $movie = new Movie();
        $movie->url = $data['url'];
        $movie->category = $data['category'] ?? 0;

        if (!$movie->create()) {
            if (Validator::contains(':')->validate($movie->message)) {
                $this->call(400, 'error', explode(':', $movie->message)[1], ['status_input' => true])->back();
                return;
            }

            $this->call(400, 'error', $movie->message)->back();
            return;
        }

        (new Csrf())->unsetToken();

        $this->back([
            'movie' => $movie->data(),
            'status_type' => 'success',
            'status_message' => MESSAGE_MOVIE_CREATE,
            'status' => true,
            'token' => (new Csrf())->insertHiddenToken()
        ]);
    }

    /**
     * @param array $data
     * 
     * @return void
     */
    public function delete(array $data): void
    {
        if (!Validator::keySet(
            Validator::key('movie', Validator::notBlank()->intVal()),
            Validator::key('csrf_token', Validator::notBlank(), false)
        )->validate($data)) {
            $this->call(400)->back();
            return;
        }

        $movie = new Movie();
        $movie->movie = $data['movie'];

        if (!$movie->delete()) {
            $this->call(400, 'error', $movie->message)->back();
            return;
        }

        (new Csrf())->unsetToken();

        $this->back([
            'movie' => $movie->data(),
            'status_type' => 'success',
            'status_message' => MESSAGE_MOVIE_DELETE,
            'status' => true,
            'token' => (new Csrf())->insertHiddenToken()
        ]);
    }
}
