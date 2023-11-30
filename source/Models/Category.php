<?php

namespace Source\Models;

use Carbon\Carbon;
use Respect\Validation\Validator;
use SleekDB\Store;

/**
 * Class Category
 * 
 * @package Source\Models
 */
class Category
{
    /** @var array|string */
    public array|string $categories;

    /** @var string */
    public string $name;

    /** @var string */
    public string $message;

    /** @var array|null */
    private ?array $data;

    /**
     * @return bool
     */
    public function create(): bool
    {
        if (!$this->validateName()) {
            return false;
        }

        $DB = new Store('categories', DIRECTORY_SLEEK, SLEEK_DATA_CONFIG);

        $searchName = $DB->findOneBy(['name', '===', strtolower($this->name)]);
        if (Validator::notBlank()->validate($searchName)) {
            $this->message = 'input:' . MESSAGE_CATEGORY_REGISTERED;
            return false;
        }

        $this->data = [
            'name' => strtolower($this->name),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => ''
        ];

        $category = $DB->insert($this->data);

        $this->data['_id'] = $category['_id'];

        return true;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $DB = new Store('categories', DIRECTORY_SLEEK, SLEEK_DATA_CONFIG);

        $categories = json_decode($this->categories);

        foreach ($categories as $value) {
            $category = $DB->findById($value);
            if (Validator::notBlank()->validate($category)) {
                $DB->deleteById($value);

                $this->data[] = $category;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function all(): array|null
    {
        $DB = (new Store('categories', DIRECTORY_SLEEK, SLEEK_DATA_CONFIG));

        return $DB->findAll(['name' => 'asc']);
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
    private function validateName(): bool
    {
        if (!Validator::intVal()->min(3)->validate(strlen($this->name))) {
            $this->message = 'input:' . MESSAGE_CATEGORY_MINIMUM;
            return false;
        }

        if (!Validator::intVal()->max(16)->validate(strlen($this->name))) {
            $this->message = 'input:' . MESSAGE_CATEGORY_MAXIMUM;
            return false;
        }

        if (!Validator::noWhitespace()->validate($this->name)) {
            $this->message = 'input:' . MESSAGE_CATEGORY_SPACE;
            return false;
        }

        if (!Validator::alpha()->validate($this->name)) {
            $this->message = 'input:' . MESSAGE_CATEGORY_CHARACTERS;
            return false;
        }

        return true;
    }
}
