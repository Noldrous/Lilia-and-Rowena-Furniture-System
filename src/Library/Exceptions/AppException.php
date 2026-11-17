<?php

declare(strict_types=1);

namespace Library\Exceptions;

use Exception;
use Throwable;

/**
 * OOP PRINCIPLE — ABSTRACTION, ENCAPSULATION, INHERITANCE & POLYMORPHISM:
 * Abstract base domain exception inherited by ValidationException,
 * AuthenticationException, and DatabaseException.
 */
abstract class AppException extends Exception
{
    private int $httpStatusCode;

    public function __construct(
        string $message,
        int $httpStatusCode = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $httpStatusCode, $previous);
        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * Encapsulated getter for the HTTP status code associated with this exception.
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * Abstract method implemented polymorphically by each exception subclass.
     */
    abstract public function getErrorCategory(): string;

    /**
     * Polymorphic serialization method for JSON API responses.
     * Subclasses override this method to attach domain-specific error metadata.
     *
     * @return array<string, mixed>
     */
    public function toErrorPayload(): array
    {
        return [
            'ok'       => false,
            'category' => $this->getErrorCategory(),
            'error'    => $this->getMessage(),
            'status'   => $this->getHttpStatusCode(),
        ];
    }
}
