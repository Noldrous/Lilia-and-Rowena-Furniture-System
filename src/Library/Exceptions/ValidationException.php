<?php

declare(strict_types=1);

namespace Library\Exceptions;

use Throwable;

/**
 * OOP PRINCIPLE — ENCAPSULATION, INHERITANCE & POLYMORPHISM:
 * Extends AppException when user input (email, password, form fields, voucher code,
 * or product pricing) fails validation, encapsulating field-level errors and
 * polymorphically overriding toErrorPayload().
 */
class ValidationException extends AppException
{
    /** @var array<string, string> */
    private array $errors;

    /**
     * @param string $message
     * @param array<string, string> $errors
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = 'Validation failed.',
        array $errors = [],
        int $code = 422,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return array<string, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getErrorCategory(): string
    {
        return 'validation_error';
    }

    /**
     * @return array<string, mixed>
     */
    public function toErrorPayload(): array
    {
        $payload = parent::toErrorPayload();
        $payload['errors'] = $this->errors;
        return $payload;
    }
}
