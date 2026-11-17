<?php

declare(strict_types=1);

namespace Library\Contracts;

/**
 * OOP PRINCIPLE — ABSTRACTION & POLYMORPHISM:
 * Defines a uniform abstract contract for custom in-memory data structures
 * (such as Library\OrderQueue and Library\HashTable) without exposing their
 * underlying storage representation (head/tail pointers vs. hash bucket chains).
 */
interface DataStructureInterface
{
    /**
     * Return the number of elements currently stored in the data structure.
     */
    public function size(): int;

    /**
     * Check whether the data structure contains zero elements.
     */
    public function isEmpty(): bool;

    /**
     * Reset and remove all stored elements from the data structure.
     */
    public function clear(): void;

    /**
     * Export all stored elements as a sequential PHP array.
     *
     * @return array<int, mixed>
     */
    public function toArray(): array;
}
