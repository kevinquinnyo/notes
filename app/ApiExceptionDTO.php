<?php
declare(strict_types=1);
namespace App;

final class APIExceptionDTO
{
    /**
     * @param string $message the exception message/error message
     * @param array $errors the specific errors associated to the action
     * @see \App\Exception\APIException
     */
    public function __construct(
        public string $message = 'Unable to process request.',
        public array $errors = []
    ) {}
}
