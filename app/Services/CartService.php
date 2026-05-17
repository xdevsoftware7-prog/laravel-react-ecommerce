<?php

namespace App\Services;

use App\Models\Product;
use App\Models\VariationTypeOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartService
{
    private ?array $cachedCartItems = null;
    protected const COOKIE_NAME = 'cartItems';
    protected const COOKIE_LIFETIME = 60 * 24 * 365; // 1 year


    public function addItemToCart(Product $product, int $quantity = 1, $optionIds = null) {}

    public function updateItemQuantity(int $productId, int $quantity, $optionIds = null) {}
    public function removeItemFromCart(int $productId, $optionIds = null) {}
    public function  getCartItems()
    {
        try {
            if ($this->cachedCartItems === null) {
                if (Auth::check()) {
                    $cartItems = $this->getCarItemsFromDatabase();
                } else {
                    $cartItems = $this->getCarItemsFromCookies();
                }
                $productIds = collect($cartItems)->map(fn($item) => $item['product_id']);
                $products = Product::whereIn('id', $productIds)->with("user.vendor")->forWebsite()->get()->keyBy("id");
                $cartItemsData = [];
                foreach ($cartItems as $key => $cartItem) {
                    $product = data_get($products, $cartItem['product_id']);
                    if (!$product) continue;

                    $optionInfo = [];
                    $options = VariationTypeOption::with("variationType")->whereIn('id', $cartItem['option_ids'])->get();

                    $imageUrl = null;
                    foreach ($cartItem['option_ids'] as $option_id) {
                        $option = data_get($options, $option_id);
                        if (!$imageUrl) {
                            $imageUrl = $option->getFirstMediaUrl('images', 'small');
                        }

                        $optionInfo[] = [
                            'id' => $option_id,
                            'name' => $option->name,
                            'type' => [
                                'id' => $option->variationType->id,
                                'name' => $option->variationType->name
                            ]
                        ];
                    }
                    $cartItemData[] = [
                        'id'=>$cartItem['id'],
                        'product_id'=>$product->id,
                        'title'=>$product->title,
                        'slug'=>$product->slug,
                        'price'=>$cartItem['price'],
                        'quantity'=>$cartItem['quantity'],
                        'option_ids'=>$cartItem['option_ids'],
                        'options'=>$optionInfo,
                        'image'=>$imageUrl ?: $product->getFirstMediaUrl('images','small'),
                        'user'=>[
                            'id'=>$product->created_by,   
                            'name'=>$product->user->vendor->store_name,
                        ]
                    ];
                }

                $this->cachedCartItems = $cartItemData;
            }
            return $this->cachedCartItems;
        } catch (\Exception $e) {
            Log::error($e->getMessage(). PHP_EOL . $e->getTraceAsString());
        }
        return [];
    }
    public function  getTotalQuantity() {
        $totalQuantity = 0;
        foreach($this->getCartItems() as $item){
            $totalQuantity += $item['quantity'];
        }
        return $totalQuantity;
    }
    public function  getTotalPrice() {
        $total = 0;
        foreach($this->getCartItems() as $item){
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    protected function updateItemQuantityInDatabase(int $productId, int $quantity, array $optionIds): void {}

    protected function saveItemToDatabase(int $productId, int $quantity, array $optionIds): void {}

    protected function saveItemToCookies(int $productId, int $quantity, array $optionIds): void {}

    protected function removeItemFromDatabase(int $productId, int $quantity, array $optionIds): void {}

    protected function removeItemFromCookies(int $productId, int $quantity, array $optionIds): void {}

    protected function getCarItemsFromDatabase() {}
    protected function getCarItemsFromCookies() {}
}
