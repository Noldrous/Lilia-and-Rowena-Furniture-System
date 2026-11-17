<?php

declare(strict_types=1);

namespace Library\Exceptions;

use Throwable;

/**
 * OOP PRINCIPLE — INHERITANCE & POLYMORPHISM:
 * Extends AppException to represent Customer or Admin authentication failures
 * (invalid email, wrong password, or unauthorized session) and overrides
 * getErrorCategory() and toErrorPayload().
 */
class AuthenticationException extends AppException
{
    public function __construct(
        string $message = 'Invalid email address or password.',
        int $code = 401,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrorCategory(): string
    {
        return 'authentication_error';
    }
}
