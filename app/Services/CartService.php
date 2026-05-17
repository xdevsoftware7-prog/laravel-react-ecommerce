<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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
                $productIds = collect($cartItems)->map(fn($item)=>$item['product_id']);
                $products = Product::whereIn('id',$productIds)->with("user.vendor");
            }
            return $this->cachedCartItems;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function  getTotalQuantity() {}
    public function  getTotalPrice() {}

    protected function updateItemQuantityInDatabase(int $productId, int $quantity, array $optionIds): void {}

    protected function saveItemToDatabase(int $productId, int $quantity, array $optionIds): void {}

    protected function saveItemToCookies(int $productId, int $quantity, array $optionIds): void {}

    protected function removeItemFromDatabase(int $productId, int $quantity, array $optionIds): void {}

    protected function removeItemFromCookies(int $productId, int $quantity, array $optionIds): void {}

    protected function getCarItemsFromDatabase() {}
    protected function getCarItemsFromCookies() {}
}
