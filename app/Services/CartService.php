<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    public const SESSION_KEY = 'cart';

    public const COUPON_SESSION_KEY = 'cart_coupon_code';

    /**
     * @return array<string, array{type: string, id: int, quantity: int}>
     */
    public function rawItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * @return Collection<int, array{
     *     key: string,
     *     type: string,
     *     id: int,
     *     quantity: int,
     *     model: Product|Issue,
     *     unit_price: float,
     *     line_subtotal: float,
     *     name: string,
     *     image_url: ?string,
     *     details: ?string
     * }>
     */
    public function getItems(?User $user = null): Collection
    {
        $items = collect();
        $raw = $this->rawItems();

        foreach ($raw as $key => $row) {
            $type = $row['type'] ?? '';
            $id = (int) ($row['id'] ?? 0);
            $quantity = max(1, (int) ($row['quantity'] ?? 1));

            if ($type === 'product') {
                $model = Product::query()->whereKey($id)->where('is_active', true)->first();
                if (! $model || $model->quantity < 1) {
                    $this->remove($key);

                    continue;
                }
                $quantity = min($quantity, $model->quantity);
                $unitPrice = (float) $model->sale_price_after_discount;
                $name = $model->name;
                $imageUrl = $model->hasMedia('images') ? $model->getFirstMediaUrl('images') : null;
                $details = $model->details;
            } elseif ($type === 'issue') {
                $model = Issue::query()->whereKey($id)->where('is_active', true)->first();
                if (! $model) {
                    $this->remove($key);

                    continue;
                }
                if ($user && $user->hasPaidPurchaseFor($model)) {
                    $this->remove($key);

                    continue;
                }
                $quantity = 1;
                $unitPrice = (float) $model->purchase_price_after_discount;
                $name = $model->title;
                $imageUrl = $model->hasMedia('main_image') ? $model->getFirstMediaUrl('main_image') : null;
                $details = $model->details;
            } else {
                $this->remove($key);

                continue;
            }

            if ($quantity !== (int) ($row['quantity'] ?? 1)) {
                $this->setRawItem($key, $type, $id, $quantity);
            }

            $items->push([
                'key' => $key,
                'type' => $type,
                'id' => $id,
                'quantity' => $quantity,
                'model' => $model,
                'unit_price' => $unitPrice,
                'line_subtotal' => round($unitPrice * $quantity, 2),
                'name' => $name,
                'image_url' => $imageUrl,
                'details' => $details,
            ]);
        }

        return $items;
    }

    public function addProduct(Product $product, int $quantity = 1): void
    {
        abort_unless($product->is_active, 404);
        if ($product->quantity < 1) {
            throw new \InvalidArgumentException('المنتج غير متوفر حالياً.');
        }

        $key = $this->makeKey('product', $product->getKey());
        $raw = $this->rawItems();
        $existing = (int) ($raw[$key]['quantity'] ?? 0);
        $newQty = min($existing + max(1, $quantity), $product->quantity);

        $this->setRawItem($key, 'product', (int) $product->getKey(), $newQty);
    }

    public function addIssue(Issue $issue, ?User $user = null): void
    {
        abort_unless($issue->is_active, 404);

        if ($user && $user->hasPaidPurchaseFor($issue)) {
            throw new \InvalidArgumentException('لقد اشتريت هذه الجريمة مسبقاً.');
        }

        $key = $this->makeKey('issue', $issue->getKey());
        $this->setRawItem($key, 'issue', (int) $issue->getKey(), 1);
    }

    public function updateQuantity(string $key, int $quantity): void
    {
        $raw = $this->rawItems();
        if (! isset($raw[$key])) {
            return;
        }

        if (($raw[$key]['type'] ?? '') !== 'product') {
            return;
        }

        if ($quantity < 1) {
            $this->remove($key);

            return;
        }

        $product = Product::query()
            ->whereKey($raw[$key]['id'])
            ->where('is_active', true)
            ->first();

        if (! $product) {
            $this->remove($key);

            return;
        }

        $quantity = min($quantity, $product->quantity);
        $this->setRawItem($key, 'product', (int) $product->getKey(), $quantity);
    }

    public function remove(string $key): void
    {
        $raw = $this->rawItems();
        unset($raw[$key]);
        Session::put(self::SESSION_KEY, $raw);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::forget(self::COUPON_SESSION_KEY);
    }

    public function getSubtotal(?User $user = null): float
    {
        return round((float) $this->getItems($user)->sum('line_subtotal'), 2);
    }

    public function count(): int
    {
        return (int) $this->getItems()->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return $this->getItems()->isEmpty();
    }

    public function getCouponCode(): ?string
    {
        $code = Session::get(self::COUPON_SESSION_KEY);

        return $code !== null && $code !== '' ? (string) $code : null;
    }

    public function setCouponCode(?string $code): void
    {
        $code = trim((string) ($code ?? ''));
        if ($code === '') {
            Session::forget(self::COUPON_SESSION_KEY);

            return;
        }

        Session::put(self::COUPON_SESSION_KEY, $code);
    }

    public function makeKey(string $type, int $id): string
    {
        return $type.'-'.$id;
    }

    /**
     * @return array{type: string, id: int}|null
     */
    public function parseKey(string $key): ?array
    {
        if (! preg_match('/^(product|issue)-(\d+)$/', $key, $m)) {
            return null;
        }

        return ['type' => $m[1], 'id' => (int) $m[2]];
    }

    public function purchasableFromLine(array $line): Model
    {
        return $line['model'];
    }

    private function setRawItem(string $key, string $type, int $id, int $quantity): void
    {
        $raw = $this->rawItems();
        $raw[$key] = [
            'type' => $type,
            'id' => $id,
            'quantity' => $type === 'issue' ? 1 : max(1, $quantity),
        ];
        Session::put(self::SESSION_KEY, $raw);
    }
}
