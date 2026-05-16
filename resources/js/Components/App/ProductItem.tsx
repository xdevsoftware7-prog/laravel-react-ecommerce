import React from "react";
import { Product } from "@/types";
import { Link } from "@inertiajs/react";
import CurrencyFormatter from "@/Components/Core/CurrencyFormatter";





function ProductItem({ product }: { product: Product }) {
  return (
    <div className="card bg-base-100 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
      <Link
        href={route("products.show", product.slug)}
        className="block aspect-square overflow-hidden"
      >
        <figure className="w-full h-full">
          <img
            src={product.image}
            alt={product.title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
          />
        </figure>
      </Link>
      <div className="card-body p-4 sm:p-5 flex flex-col gap-2">
        <h2 className="card-title text-lg font-semibold line-clamp-2 hover:text-primary transition-colors">
          <Link href={route("products.show", product.slug)}>
            {product.title}
          </Link>
        </h2>
        <p className="text-sm text-base-content/70">
          by{" "}
          <Link
            href="/"
            className="hover:text-primary font-medium transition-colors"
          >
            {product.user.name}
          </Link>{" "}
          in{" "}
          <Link
            href="/"
            className="hover:text-primary font-medium transition-colors"
          >
            {product.departement.name}
          </Link>
        </p>
        <div className="card-actions items-center justify-between mt-auto pt-4 border-t border-base-200">
          <span className="text-xl sm:text-2xl font-bold text-primary">
            <CurrencyFormatter amount={product.price} />
          </span>
          <button className="btn btn-primary btn-sm sm:btn-md">
            Add to Cart
          </button>
        </div>
      </div>
    </div>
  );
}

export default ProductItem;
