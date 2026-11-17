<?php

declare(strict_types=1);

namespace Library\Exceptions;

use Throwable;

/**
 * OOP PRINCIPLE — ENCAPSULATION, INHERITANCE & POLYMORPHISM:
 * Extends AppException to represent database connection or SQL query failures,
 * encapsulating the SQLSTATE code and polymorphically enriching toErrorPayload().
 */
class DatabaseException extends AppException
{
    private string $sqlState;

    public function __construct(
        string $message = 'A database error occurred.',
        int $code = 500,
        ?Throwable $previous = null,
        string $sqlState = 'HY000'
    ) {
        parent::__construct($message, $code, $previous);
        $this->sqlState = $sqlState;
    }

    public function getSqlState(): string
    {
        return $this->sqlState;
    }

    public function getErrorCategory(): string
    {
        return 'database_error';
    }

    /**
     * @return array<string, mixed>
     */
    public function toErrorPayload(): array
    {
        $payload = parent::toErrorPayload();
        $payload['sqlState'] = $this->sqlState;
        return $payload;
    }
}
