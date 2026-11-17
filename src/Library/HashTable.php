<?php

declare(strict_types=1);

namespace Library;

use Library\Contracts\DataStructureInterface;

/**
 * Explicit Hash Table with Polynomial Rolling ASCII Hash Function & Separate Chaining (PSR-4: Library\HashTable).
 *
 * OOP PRINCIPLES APPLIED:
 * - Abstraction & Polymorphism: Implements DataStructureInterface (`size()`, `isEmpty()`, `clear()`, `toArray()`).
 * - Encapsulation: Bucket chains (`$buckets`), bucket count (`$bucketCount`), and entry count (`$count`)
 *   are strictly `private` and accessed only via controlled methods.
 *
 * // ALGORITHM B5: Hash function
 * // Chapter: Chapter 9 (Hash Tables)
 * // Purpose: Converts string keys (Voucher Codes, Product IDs, Order IDs, and Customer Emails)
 * //          into bucket indices using ASCII character codes % table size, paired with Separate
 * //          Chaining collision resolution for O(1) average lookup and duplicate order detection.
 * // Time Complexity: O(key length) for hash computation; O(1) average insert/search/delete (O(n) worst case)
 * // Space Complexity: O(n + m) where n is the number of stored key-value pairs and m is the bucket count
 */
class HashTable implements DataStructureInterface
{
    /** @var array<int, array<int, array{key: string, value: mixed}>> Fixed-size array of bucket chains */
    private array $buckets;

    /** @var int Number of buckets (chosen as a prime number to distribute keys evenly) */
    private int $bucketCount;

    /** @var int Number of unique keys currently stored */
    private int $count = 0;

    public function __construct(int $bucketCount = 53)
    {
        $this->bucketCount = max(7, $bucketCount);
        $this->buckets = array_fill(0, $this->bucketCount, []);
    }

    /**
     * Normalize a string key consistently (trimmed, uppercase) for case-insensitive lookup.
     */
    private static function normalizeKey(string $key): string
    {
        return strtoupper(trim($key));
    }

    /**
     * // ALGORITHM B5: Hash function
     * // Chapter: Chapter 9 (Hash Tables)
     * // Purpose: Turns a string key into a valid bucket number (0 to bucketCount - 1)
     * //          by summing weighted ASCII character codes modulo the table size.
     * // Time Complexity: O(key length)
     * // Space Complexity: O(1) auxiliary space
     */
    public static function computeBucketIndex(string $key, int $bucketCount = 53): int
    {
        $safeBuckets = max(1, $bucketCount);
        $normalized = self::normalizeKey($key);
        $length = strlen($normalized);
        $hashSum = 0;

        for ($i = 0; $i < $length; $i++) {
            $hashSum = ($hashSum * 31 + ord($normalized[$i])) % $safeBuckets;
        }

        return $hashSum < 0 ? $hashSum + $safeBuckets : $hashSum;
    }

    /**
     * Instance hash helper that delegates to the polynomial rolling ASCII hash function.
     */
    public function hashKey(string $key, ?int $customBucketCount = null): int
    {
        return self::computeBucketIndex($key, $customBucketCount ?? $this->bucketCount);
    }

    /**
     * Return the encapsulated bucket count.
     */
    public function getBucketCount(): int
    {
        return $this->bucketCount;
    }

    /**
     * Insert or update a key-value pair in the computed bucket chain (Separate Chaining).
     */
    public function put(string $key, mixed $value): void
    {
        $normalizedKey = self::normalizeKey($key);
        $bucketIndex = self::computeBucketIndex($normalizedKey, $this->bucketCount);
        $chain = &$this->buckets[$bucketIndex];
        $chainLength = count($chain);

        for ($i = 0; $i < $chainLength; $i++) {
            if ($chain[$i]['key'] === $normalizedKey) {
                $chain[$i]['value'] = $value;
                return;
            }
        }

        $chain[] = [
            'key'   => $normalizedKey,
            'value' => $value,
        ];
        $this->count++;
    }

    /**
     * Retrieve the value associated with a key in O(1) average time, or return $default if not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $normalizedKey = self::normalizeKey($key);
        $bucketIndex = self::computeBucketIndex($normalizedKey, $this->bucketCount);
        $chain = $this->buckets[$bucketIndex];
        $chainLength = count($chain);

        for ($i = 0; $i < $chainLength; $i++) {
            if ($chain[$i]['key'] === $normalizedKey) {
                return $chain[$i]['value'];
            }
        }

        return $default;
    }

    /**
     * Check whether a key exists in the hash table in O(1) average time.
     */
    public function has(string $key): bool
    {
        $normalizedKey = self::normalizeKey($key);
        $bucketIndex = self::computeBucketIndex($normalizedKey, $this->bucketCount);
        $chain = $this->buckets[$bucketIndex];
        $chainLength = count($chain);

        for ($i = 0; $i < $chainLength; $i++) {
            if ($chain[$i]['key'] === $normalizedKey) {
                return true;
            }
        }

        return false;
    }

    /**
     * Delete a key-value pair from its bucket chain in O(1) average time.
     */
    public function remove(string $key): bool
    {
        $normalizedKey = self::normalizeKey($key);
        $bucketIndex = self::computeBucketIndex($normalizedKey, $this->bucketCount);
        $chain = &$this->buckets[$bucketIndex];
        $chainLength = count($chain);

        for ($i = 0; $i < $chainLength; $i++) {
            if ($chain[$i]['key'] === $normalizedKey) {
                array_splice($chain, $i, 1);
                $this->count--;
                return true;
            }
        }

        return false;
    }

    /**
     * Return total number of stored entries.
     */
    public function size(): int
    {
        return $this->count;
    }

    /**
     * Check whether the hash table contains zero entries.
     */
    public function isEmpty(): bool
    {
        return $this->count === 0;
    }

    /**
     * Clear all bucket chains and reset entry count to zero.
     */
    public function clear(): void
    {
        $this->buckets = array_fill(0, $this->bucketCount, []);
        $this->count = 0;
    }

    /**
     * Return all stored values across all bucket chains.
     *
     * @return array<int, mixed>
     */
    public function values(): array
    {
        $all = [];
        for ($b = 0; $b < $this->bucketCount; $b++) {
            $chain = $this->buckets[$b];
            $chainLen = count($chain);
            for ($i = 0; $i < $chainLen; $i++) {
                $all[] = $chain[$i]['value'];
            }
        }

        return $all;
    }

    /**
     * Export all stored values as a sequential array (DataStructureInterface implementation).
     *
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        return $this->values();
    }
}
