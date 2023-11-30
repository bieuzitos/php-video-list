<?php

namespace Source\Models;

use Carbon\Carbon;
use Respect\Validation\Validator;
use SleekDB\Store;

/**
 * Class Movie
 * 
 * @package Source\Models
 */
class Movie
{
    /** @var int */
    public int $movie;

    /** @var string */
    public string $url;

    /** @var string */
    public string $category;

    /** @var string */
    public string $message;

    /** @var array|null */
    private ?array $data;

    /**
     * @return bool
     */
    public function create(): bool
    {
        if (!$this->validateUrl()) {
            return false;
        }

        $DB = new Store('movies', DIRECTORY_SLEEK, SLEEK_DATA_CONFIG);

        $this->data = [
            'url' => $this->url,
            'category' => $this->category,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => ''
        ];

        $movie = $DB->insert($this->data);

        $this->data['_id'] = $movie['_id'];

        return true;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $DB = new Store('movies', DIRECTORY_SLEEK, SLEEK_DATA_CONFIG);

        $movie = $DB->findById($this->movie);
        if (!Validator::notBlank()->validate($movie)) {
            $this->message = MESSAGE_MOVIE_INVALID;
            return false;
        }

        $DB->deleteById($this->movie);

        $this->data = $movie;

        return true;
    }

    /**
     * @return array
     */
    public function all(): array|null
    {
        $DB = (new Store('movies', DIRECTORY_SLEEK, SLEEK_DATA_CONFIG));

        return $DB->findAll(['created_at' => 'desc']);
    }

    /**
     * @return array
     */
    public function data(): array|null
    {
        return $this->data ?? null;
    }

    /**
     * @return bool
     */
    private function validateUrl(): bool
    {
        $DB = new Store('movies', DIRECTORY_SLEEK, SLEEK_DATA_CONFIG);

        if (!Validator::url()->validate($this->url)) {
            $this->message = 'input:' . MESSAGE_URL_INVALID;
            return false;
        }

        $allowedExtensions = ['m3u8', 'mp4'];

        $urlInfo = pathinfo($this->url);
        $urlExtension = strtolower($urlInfo['extension'] ?? '');

        if (!in_array($urlExtension, $allowedExtensions)) {
            $this->message = 'input:' . MESSAGE_URL_INVALID_VIDEO;
            return false;
        }

        $searchByUrl = $DB->findOneBy(['url', '===', $this->url]);
        if (Validator::notBlank()->validate($searchByUrl)) {
            $this->message = 'input:' . MESSAGE_URL_REGISTERED;
            return false;
        }

        return true;
    }
}
