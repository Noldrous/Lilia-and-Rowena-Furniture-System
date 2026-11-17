<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeImmutable;
use DateTimeZone;
use Library\Database;

/**
 * OOP PRINCIPLES — ABSTRACTION, ENCAPSULATION, INHERITANCE & POLYMORPHISM:
 * Abstract base domain model inherited by User, Product (StandardProduct, CustomizedProduct),
 * and AnalyticsReport.
 *
 * - Encapsulation: Protects the underlying Database instance (`protected Database $db`) and
 *   exposes controlled persistence and timezone helpers to subclasses.
 * - Abstraction: Hides low-level datastore read/write details behind high-level model methods
 *   and defines abstract contracts (`getEntityName()`, `getTableName()`, `validatePayload()`)
 *   that every concrete model must implement.
 * - Inheritance: Shared by all domain models in `App\Models`.
 * - Polymorphism: Subclasses override `getEntityName()`, `getTableName()`, and `validatePayload()`
 *   with entity-specific validation and metadata rules.
 */
abstract class BaseModel
{
    protected Database $db;

    public function __construct(?Database $db = null)
    {
        $this->db = $db ?? Database::getInstance();
    }

    /**
     * Encapsulated read-only getter for the Database connection wrapper.
     */
    public function getDatabase(): Database
    {
        return $this->db;
    }

    /**
     * Abstract contract: human-readable domain entity name (overridden polymorphically by subclasses).
     */
    abstract public function getEntityName(): string;

    /**
     * Abstract contract: primary SQL table mapped to this domain model.
     */
    abstract public function getTableName(): string;

    /**
     * Abstract contract: validate and normalize an associative input payload for this entity.
     *
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    abstract public function validatePayload(array $input): array;

    /**
     * Protected helper that abstracts reading the full datastore for subclasses.
     *
     * @return array<string, mixed>
     */
    protected function readStoreData(): array
    {
        return $this->db->readStore();
    }

    /**
     * Protected helper that abstracts persisting the updated datastore for subclasses.
     *
     * @param array<string, mixed> $store
     */
    protected function writeStoreData(array $store): void
    {
        $this->db->writeStore($store);
    }

    /**
     * Protected helper indicating whether live MySQL PDO connectivity is active.
     */
    protected function isDatabaseConnected(): bool
    {
        return $this->db->isMySqlConnected();
    }

    /**
     * Shared Asia/Manila timezone factory for consistent timestamp handling across models.
     */
    protected function getManilaTimeZone(): DateTimeZone
    {
        return new DateTimeZone('Asia/Manila');
    }

    /**
     * Shared current Asia/Manila DateTimeImmutable helper.
     */
    protected function nowManila(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->getManilaTimeZone());
    }
}
