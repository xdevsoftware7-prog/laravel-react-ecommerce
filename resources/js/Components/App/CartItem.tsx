import { Link } from "@inertiajs/react";
import React from "react";
import CurrencyFormatter from "../Core/CurrencyFormatter";

function CartItem({ item }: { item: any }) {
  return (
    <div>
      <div className="flex gap-4 mb-4">
        <Link
          href={route("product.show", item.slug)}
          className="w-16 h-16 justify-center items-center"
        >
          <img
            src={item.image}
            alt={item.title}
            className="max-w-full max-h-full"
          />
        </Link>
        <div className="flex-1">
          <h3 className="mb-3 font-semibold">
            <Link href={route("product.show", item.slug)}>{item.title}</Link>
          </h3>
          <div className="flex justify-between text-sm">
            <div className="">Quantity: {item.quantity}</div>
            <div className="">
              <CurrencyFormatter
                amount={item.quantity * item.price}
              ></CurrencyFormatter>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default CartItem;
