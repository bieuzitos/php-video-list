<?php

namespace Source\Http\Middlewares;

use Source\Support\Csrf;

/**
 * Class CsrfMiddleware
 * 
 * @package Source\Http\Middlewares
 */
class CsrfMiddleware
{
    /**
     * @return bool
     */
    public function handle(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!(new Csrf)->validate()) {
                echo json_encode([
                    'status_message' => MESSAGE_REQUEST_ERROR,
                    'status' => false
                ]);
                exit;
            }
        }

        return true;
    }
}
