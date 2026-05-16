import ProductItem from "@/Components/App/ProductItem";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps } from "@/types";
import { Head, Link } from "@inertiajs/react";
import { PaginationProps } from "@/types";
import { Product } from "@/types";

export default function Home({
  products,
}: PageProps<{ products: PaginationProps<Product> }>) {
  return (
    <AuthenticatedLayout>
      <Head title="Home" />
      <div className="hero bg-gray-base-200 h-[300px]">
        <div className="hero-content text-center">
          <div className="max-w-md">
            <h1 className="text-5xl font-bold">Hello there</h1>
            <p className="py-6">
              Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda
              excepturi exercitationem quasi. In deleniti eaque aut repudiandae
              et a id nisi.
            </p>
            <button className="btn btn-primary">Get Started</button>
          </div>
        </div>
      </div>
      <div className="container mx-auto px-4 md:px-8 pb-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
          {products?.data?.map((product: any) => (
            <ProductItem key={product.id} product={product} />
          ))}
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
