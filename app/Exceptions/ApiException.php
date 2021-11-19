<?php
declare(strict_types=1);
namespace App\Exceptions;

use App\APIExceptionDTO;
use Illuminate\Http\Response;

class ApiException extends \Exception
{
    public const MESSAGE_KEY = 'message';

    public const ERRORS_KEY = 'errors';

    /**
     * @var APIExceptionDTO
     */
    private $dto;

    /**
     * @param string $message the exception message
     * @param int $code the http status code
     * @param \Throwable $previous the previous throwable for exception chaining
     */
    public function __construct(APIExceptionDTO $dto, int $code, \Throwable $previous = null)
    {
        $this->dto = $dto;

        parent::__construct($dto->message, $code, $previous);
    }

    /**
     * Render this exception as an HTTP response.
     *
     * @return Response
     */
    public function render(): Response
    {
        $body = [
            self::MESSAGE_KEY => $this->dto->message,
            self::ERRORS_KEY => $this->dto->errors,
        ];

        return new Response($body, $this->getCode());
    }
}
