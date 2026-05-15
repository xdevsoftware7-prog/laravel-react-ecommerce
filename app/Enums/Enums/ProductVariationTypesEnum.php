<?php

namespace App\Enums\Enums;

enum ProductVariationTypesEnum: string
{
    case Select = 'Select';
    case Radio = 'Radio';
    case Image = 'Image';

    public static function lables(): array
    {
        return [
            self::Select->value => __('Select'),
            self::Radio->value => __('Radio'),
            self::Image->value => __('Image')
        ];
    }
}
