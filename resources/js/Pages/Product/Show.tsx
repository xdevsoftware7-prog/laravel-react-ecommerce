import React from "react";
import { Product, VariationTypeOption } from "@/types";
import { Head, useForm } from "@inertiajs/react";
import { usePage } from "@inertiajs/react";
import { useEffect, useState, useMemo } from "react";
import CurrencyFormatter from "@/Components/Core/CurrencyFormatter";
import { router } from "@inertiajs/react";
import Carousel from "@/Components/Core/Carousel";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

const arrayAreEqual = (a: any[], b: any[]) => {
  if (a.length !== b.length) return false;
  for (let i = 0; i < a.length; i++) {
    // Use loose equality or string conversion to handle number vs string differences from JSON
    if (String(a[i]) !== String(b[i])) return false;
  }
  return true;
};

export default function Show({
  product,
  variationsOptions,
}: {
  product: Product;
  variationsOptions: number[];
}) {
  const form = useForm<{
    option_ids: Record<string, number>;
    quantity: number;
    price: number | null;
  }>({
    option_ids: {},
    quantity: 1,
    price: null,
  });

  const { url } = usePage();

  const [selectedOptions, setSelectedOptions] = useState<
    Record<number, VariationTypeOption>
  >([]);

  const images = useMemo(() => {
    let combinedImages = [];
    for (let typeId in selectedOptions) {
      const option = selectedOptions[typeId];
      if (option && option.images.length > 0) {
        combinedImages.push(...option.images);
      }
    }
    combinedImages.push(...product.images);

    // Remove any duplicate images by matching the exact file name from the URL
    // so images uploaded to both the option and the product don't show twice
    return Array.from(
      new Map(
        combinedImages.map((img) => [
          img.large.split("/").pop(), // Use the file name as the unique key
          img,
        ]),
      ).values(),
    );
  }, [product, selectedOptions]);

  const computedProduct = useMemo(() => {
    const selectedOptionIds = Object.values(selectedOptions)
      .map((o) => o.id)
      .sort();

    for (let variation of product.variations) {
      const optionIds = variation.variation_type_option_ids.sort();

      if (arrayAreEqual(selectedOptionIds, optionIds)) {
        return {
          price: variation.price,
          quantity:
            variation.quantity === null ? Number.MAX_VALUE : variation.quantity,
        };
      }
    }
    return {
      price: product.price,
      quantity: product.quantity === null ? Number.MAX_VALUE : product.quantity,
    };
  }, [product, selectedOptions]);

  useEffect(() => {
    for (let type of product.variationTypes) {
      const selectedOptionId: number = variationsOptions[type.id || 0];
      console.log(selectedOptionId, type.options);
      chooseOption(
        type.id,
        type.options.find((o) => o.id == selectedOptionId) || type.options[0],
        false,
      );
    }
  }, []);

  const getOptionIdsMap = (newOptions: object) => {
    return Object.fromEntries(
      Object.entries(newOptions).map(([typeId, option]) => [typeId, option.id]),
    );
  };

  const chooseOption = (
    typeId: number,
    option: VariationTypeOption,
    updateUrl: boolean = true,
  ) => {
    setSelectedOptions((prevSelectedOptions) => {
      const newOptions = {
        ...prevSelectedOptions,
        [typeId]: option,
      };
      if (updateUrl) {
        router.get(
          url,
          {
            options: getOptionIdsMap(newOptions),
          },
          {
            preserveState: true,
            preserveScroll: true,
          },
        );
      }
      return newOptions;
    });
  };

  const onQuantityChange = (ev: React.ChangeEvent<HTMLInputElement>) => {
    const quantity = parseInt(ev.target.value);
    form.setData("quantity", quantity);
  };

  const addToCart = () => {
    form.post(route("cart.store", product.id), {
      preserveScroll: true,
      preserveState: true,
      onError: (errors) => {
        console.log(errors);
      },
    });
  };

  const renderProductVariationTypes = () => {
    return product.variationTypes.map((type) => (
      <div key={type.id} className="mb-6">
        <h3 className="text-sm font-bold uppercase tracking-wider text-base-content/80 mb-3">
          {type.name}
        </h3>
        <div className="flex flex-wrap gap-2">
          {type.options.map((option) => {
            const isSelected = selectedOptions[type.id]?.id === option.id;
            if (
              type.type === "Image" &&
              option.images &&
              option.images.length > 0
            ) {
              return (
                <button
                  key={option.id}
                  onClick={() => chooseOption(type.id, option)}
                  className={`w-16 h-16 rounded-xl overflow-hidden shadow-sm transition-all duration-300 ${
                    isSelected
                      ? "ring-4 ring-primary ring-offset-2 scale-105"
                      : "ring-1 ring-base-300 hover:ring-primary/50 opacity-80 hover:opacity-100 hover:scale-105"
                  }`}
                  title={option.name}
                >
                  <img
                    src={option.images[0].thumb}
                    alt={option.name}
                    className="w-full h-full object-cover"
                  />
                </button>
              );
            }

            return (
              <button
                key={option.id}
                onClick={() => chooseOption(type.id, option)}
                className={`btn btn-sm md:btn-md rounded-full px-6 transition-all duration-300 ${
                  isSelected
                    ? "btn-primary shadow-lg shadow-primary/30"
                    : "btn-outline border-base-300 hover:border-primary text-base-content/80"
                }`}
              >
                {option.name}
              </button>
            );
          })}
        </div>
      </div>
    ));
  };

  const renderAddToCartButton = () => {
    return (
      <div className="mt-8 flex items-center gap-4 bg-base-200/50 p-4 rounded-3xl border border-base-200">
        <div className="flex items-center gap-3">
          <span className="text-base-content/70 font-medium pl-2">Qty:</span>
          <select
            value={form.data.quantity}
            onChange={(e) => form.setData("quantity", parseInt(e.target.value))}
            className="select select-bordered select-primary w-20 md:w-24 rounded-2xl bg-base-100 font-bold"
          >
            {Array.from({
              length: Math.min(10, computedProduct.quantity || 1),
            }).map((_, i) => (
              <option key={i + 1} value={i + 1}>
                {i + 1}
              </option>
            ))}
          </select>
        </div>
        <button
          onClick={addToCart}
          className="btn btn-primary flex-1 rounded-2xl text-lg font-bold shadow-lg shadow-primary/40 hover:shadow-primary/60 hover:-translate-y-0.5 transition-all duration-300"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            className="h-5 w-5 mr-1"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
          </svg>
          Add to Cart
        </button>
      </div>
    );
  };

  useEffect(() => {
    const idsMap = Object.fromEntries(
      Object.entries(selectedOptions).map(
        ([typeId, option]: [string, VariationTypeOption]) => [
          typeId,
          option.id,
        ],
      ),
    );
    console.log(idsMap);

    form.setData("option_ids", idsMap);
  }, [selectedOptions]);

  return (
    <AuthenticatedLayout>
      <Head title={product.title} />
      <div className="container mx-auto p-4 md:p-8 lg:px-24">
        <div className="grid gap-12 grid-cols-1 lg:grid-cols-12">
          <div className="col-span-1 lg:col-span-7">
            <Carousel images={images} />
          </div>
          <div className="col-span-1 lg:col-span-5 flex flex-col pt-4">
            <h1 className="text-4xl md:text-5xl font-extrabold tracking-tight text-base-content mb-4 leading-tight">
              {product.title}
            </h1>

            <div className="flex items-center gap-4 mb-8">
              <CurrencyFormatter
                amount={computedProduct.price}
                
              />

              {computedProduct.quantity != undefined &&
                computedProduct.quantity < 10 && (
                  <div className="badge badge-error badge-outline animate-pulse font-semibold mt-1">
                    Only {computedProduct.quantity} left
                  </div>
                )}
            </div>

            <div className="w-full h-px bg-base-200 mb-8" />

            <div className="flex-1">{renderProductVariationTypes()}</div>

            {renderAddToCartButton()}

            <div className="mt-12 pt-8 border-t border-base-200">
              <h2 className="text-xl font-bold mb-4 flex items-center gap-2 text-base-content/90">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="h-6 w-6 text-primary"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                Product Details
              </h2>
              <div className="prose prose-sm md:prose-base text-base-content/80 wysiwyg-output leading-relaxed">
                <div
                  dangerouslySetInnerHTML={{ __html: product.description }}
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
