<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array|string $errors
     * @param int|null $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($errors, ?int $code = 400)
    {
        $message = '';

        if (is_array($errors)) {
            foreach ($errors as $error) {
               $message = is_array($error) ? $error[0] : $error;
            }
        } else {
            $message = $errors;
        }

        return response()->json(['error' => $message], $code);
    }
}
