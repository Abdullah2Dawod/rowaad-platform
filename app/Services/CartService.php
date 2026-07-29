<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * Session-backed shopping cart.
 *
 * Cart items are stored as an array of associative arrays keyed by a stable
 * signature (purchasable_type + purchasable_id) so that adding the same item
 * twice just bumps its quantity.
 *
 * Storage: Laravel's encrypted session driver → payloads are opaque to the
 * client. No card details ever pass through this class — payment info goes
 * straight to the gateway.
 */
class CartService
{
    protected const SESSION_KEY = 'shopping_cart';

    public function all(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return array_sum(array_column($this->all(), 'quantity'));
    }

    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    public function subtotal(): float
    {
        return array_reduce($this->all(), fn ($carry, $item) => $carry + ((float) $item['unit_price'] * (int) $item['quantity']), 0.0);
    }

    /** Add or bump an item. Type must be a Purchasable morph key. */
    public function add(string $type, int $id, string $title, float $unitPrice, int $qty = 1, array $meta = []): void
    {
        $items = $this->all();
        $key   = $this->key($type, $id);

        if (isset($items[$key])) {
            $items[$key]['quantity'] += $qty;
        } else {
            $items[$key] = [
                'purchasable_type' => $type,
                'purchasable_id'   => $id,
                'title'            => $title,
                'unit_price'       => round($unitPrice, 2),
                'quantity'         => max(1, $qty),
                'meta'             => $meta,
            ];
        }

        Session::put(self::SESSION_KEY, $items);
    }

    public function remove(string $type, int $id): void
    {
        $items = $this->all();
        unset($items[$this->key($type, $id)]);
        Session::put(self::SESSION_KEY, $items);
    }

    public function setQuantity(string $type, int $id, int $qty): void
    {
        $items = $this->all();
        $key   = $this->key($type, $id);
        if (! isset($items[$key])) return;

        if ($qty <= 0) {
            unset($items[$key]);
        } else {
            $items[$key]['quantity'] = $qty;
        }
        Session::put(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function has(string $type, int $id): bool
    {
        return isset($this->all()[$this->key($type, $id)]);
    }

    protected function key(string $type, int $id): string
    {
        return $type . '#' . $id;
    }
}
