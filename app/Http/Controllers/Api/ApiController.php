<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\ApiExceptionDTO;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

abstract class ApiController extends Controller
{
    /**
     * Return a 201 with an empty response body. Use this when a new resource is created.
     *
     * @return Response
     */
    protected function newResourceCreatedResponse(): Response
    {
        return new Response('', 201);
    }

    /**
     * @param array $errors the errors to add to the response
     * @return void
     */
    protected function throw(string $message, array $errors = [], $statusCode = 400): void
    {
        throw new ApiException(new ApiExceptionDTO($message, $errors), $statusCode);
    }
}
