import { Image } from "@/types";
import React, { useEffect, useState } from "react";

export default function Carousel({ images }: { images: Image[] }) {
  const [selectedImage, setSelectedImage] = useState<Image | null>(null);

  // Default to the first image whenever the 'images' array changes
  useEffect(() => {
    if (images && images.length > 0) {
      setSelectedImage(images[0]);
    }
  }, [images]);

  if (!images || images.length === 0) {
    return (
      <div className="text-gray-500">No images available for this product</div>
    );
  }

  return (
    <>
      <div className="flex items-start gap-8">
        <div className="flex flex-col items-center gap-2 py-2 w-16">
          {images.map((image, i) => (
            <button
              key={image.id}
              onClick={() => setSelectedImage(image)}
              className={`border-2 rounded-lg overflow-hidden w-16 h-16 block transition-all ${
                selectedImage?.id === image.id
                  ? "border-primary scale-110"
                  : "hover:border-primary opacity-70 hover:opacity-100"
              }`}
            >
              <img
                src={image.thumb}
                alt={`Thumbnail ${image.id}`}
                className="w-full h-full object-cover"
              />
            </button>
          ))}
        </div>
        <div className="carousel w-full rounded-2xl overflow-hidden shadow-lg h-[500px]">
          <div className="carousel-item w-full h-full">
            {selectedImage && (
              <img
                src={selectedImage.large}
                alt={`Image ${selectedImage.id}`}
                className="w-full h-full object-cover transition-opacity duration-300"
              />
            )}
          </div>
        </div>
      </div>
    </>
  );
}
