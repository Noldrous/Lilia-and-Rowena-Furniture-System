<?php

declare(strict_types=1);

namespace App\Models;

/**
 * OOP PRINCIPLE — INHERITANCE & POLYMORPHISM:
 * Represents a Bespoke / Customized Furniture Piece originating from a Messenger design consultation.
 * Inherits shared catalog search, sorting, and validation behavior from Product (and BaseModel)
 * while overriding product type, origin, and bespoke customization metadata polymorphically.
 */
class CustomizedProduct extends Product
{
    public function getEntityName(): string
    {
        return 'CustomizedProduct';
    }

    public function getProductTypeKey(): string
    {
        return 'customized';
    }

    public function getOriginLabel(): string
    {
        return 'Messenger customization';
    }

    public function isCustomDesign(): bool
    {
        return true;
    }

    /**
     * Polymorphic catalog record builder for Customized Messenger Products.
     * Preserves bespoke Messenger conversation IDs and customization notes while keeping
     * the piece publicly visible and purchasable in the showroom catalog.
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
            'id'                      => (string) ($validated['id'] ?? ('furn-cust-' . strtolower($sku))),
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
            'messengerConversationId' => $validated['messengerConversationId'] ?? null,
            'customizationNotes'      => $validated['customizationNotes'] ?? null,
        ];
    }
}
