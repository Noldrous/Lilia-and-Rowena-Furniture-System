<?php

declare(strict_types=1);

namespace App\Models;

/**
 * OOP PRINCIPLE — INHERITANCE & POLYMORPHISM:
 * Represents a Standard Showroom Furniture Piece created by the Admin.
 * Inherits shared catalog search, sorting, and validation behavior from Product (and BaseModel)
 * while overriding product type, origin, and catalog serialization rules polymorphically.
 */
class StandardProduct extends Product
{
    public function getEntityName(): string
    {
        return 'StandardProduct';
    }

    public function getProductTypeKey(): string
    {
        return 'standard';
    }

    public function getOriginLabel(): string
    {
        return 'Admin';
    }

    public function isCustomDesign(): bool
    {
        return false;
    }

    /**
     * Polymorphic catalog record builder for Standard Showroom Products.
     *
     * @param array<string, mixed> $validated
     * @return array<string, mixed>
     */
    protected function buildSpecializedCatalogRecord(array $validated): array
    {
        $sku = (string) $validated['sku'];
        $price = (float) $validated['price'];
        $discountPercent = (float) $validated['discountPercent'];

        return [
            'id'                      => (string) ($validated['id'] ?? ('furn-std-' . strtolower($sku))),
            'productId'               => $sku,
            'sku'                     => $sku,
            'name'                    => (string) $validated['name'],
            'image'                   => (string) $validated['image'],
            'productType'             => $this->getProductTypeKey(),
            'origin'                  => $this->getOriginLabel(),
            'isCustomClientDesign'    => $this->isCustomDesign(),
            'publiclyVisible'         => true,
            'canBePurchased'          => true,
            'adminEditable'           => true,
            'customClientEmail'       => null,
            'categoryId'              => (string) $validated['categoryId'],
            'price'                   => round($price),
            'discountPercent'         => round($discountPercent),
            'effectivePrice'          => round($price * (1.0 - $discountPercent / 100.0)),
            'dimensions'              => (string) $validated['dimensions'],
            'messengerConversationId' => null,
            'customizationNotes'      => null,
        ];
    }
}
