<?php

namespace Source\Http\Controllers\Api\v1;

use Source\Http\Controllers\Api\ApiController;

use Source\Models\Category;

use Respect\Validation\Validator;
use Source\Support\Csrf;

/**
 * Class CategoryController
 * 
 * @package Source\Http\Controllers\Api\v1
 */
class CategoryController extends ApiController
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
            Validator::key('category', Validator::notBlank()),
            Validator::key('csrf_token', Validator::notBlank(), false)
        )->validate($data)) {
            $this->call(400)->back();
            return;
        }

        $category = new Category();
        $category->name = $data['category'];

        if (!$category->create()) {
            if (Validator::contains(':')->validate($category->message)) {
                $this->call(400, 'error', explode(':', $category->message)[1], ['status_input' => true])->back();
                return;
            }

            $this->call(400, 'error', $category->message)->back();
            return;
        }

        (new Csrf())->unsetToken();

        $this->back([
            'category' => $category->data(),
            'status_type' => 'success',
            'status_message' => MESSAGE_CATEGORY_CREATE,
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
            Validator::key('categories', Validator::notBlank()),
            Validator::key('csrf_token', Validator::notBlank(), false)
        )->validate($data)) {
            $this->call(401)->back();
            return;
        }

        $category = new Category();
        $category->categories = $data['categories'];

        if (!$category->delete()) {
            if (Validator::contains(':')->validate($category->message)) {
                $this->call(400, 'error', explode(':', $category->message)[1], ['status_input' => true])->back();
                return;
            }

            $this->call(400, 'error', $category->message)->back();
            return;
        }

        (new Csrf())->unsetToken();

        $this->back([
            'categories' => $category->data(),
            'status_type' => 'success',
            'status_message' => MESSAGE_CATEGORY_DELETE,
            'status' => true,
            'token' => (new Csrf())->insertHiddenToken()
        ]);
    }

    /**
     * @return void
     */
    public function list(): void
    {
        (new Csrf())->unsetToken();

        $this->back([
            'categories' => (new Category())->all(),
            'status_type' => 'success',
            'status_message' => MESSAGE_CATEGORY_CREATE,
            'status' => true,
            'token' => (new Csrf())->insertHiddenToken()
        ]);
    }
}
