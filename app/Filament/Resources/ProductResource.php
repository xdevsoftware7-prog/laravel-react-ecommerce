<?php

namespace App\Filament\Resources;

use App\Enums\Enums\ProductStatusEnum;
use App\Enums\RolesEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ProductImages;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use App\Models\Product;
use App\Models\ProductVariation;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Override;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'zondicon-list';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([
                    TextInput::make('title')->live(true)->required()->afterStateUpdated(function (string $operation, $state, callable $set) {
                        $set("slug", Str::slug($state));
                    }),
                    TextInput::make('slug')->required(),
                    Select::make('departement_id')->relationship('departement', 'name')->label(__('Departement'))->preload()->searchable()->required()->reactive()->afterStateUpdated(function (callable $set) {
                        $set('category_id', null);
                    }),
                    Select::make('category_id')->relationship(
                        name: 'Category',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder  $query, callable $get) {
                            $departement = $get('departement_id');
                            if ($departement) {
                                $query->where('departement_id', $departement);
                            }
                        }
                    )->label(__('Category'))->preload()->searchable()->required(),
                    RichEditor::make('description')->required()->columnSpan(2)->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                        'table'
                    ]),
                    TextInput::make('price')->required()->numeric(),
                    TextInput::make('quantity')->integer(),
                    Select::make('status')->options(ProductStatusEnum::labels())->default(ProductStatusEnum::Draft->value)->required()

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')->collection('images')->limit(1)->conversion('thumb')->label('image'),
                TextColumn::make('title')->sortable()->words(10)->searchable(),
                TextColumn::make('status')->badge()->colors(ProductStatusEnum::colors()),
                TextColumn::make('departement.name'),
                TextColumn::make("category.name"),
                TextColumn::make("created_at")->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options(ProductStatusEnum::labels()),
                SelectFilter::make('departement_id')->relationship('departement', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'images' => Pages\ProductImages::route('/{record}/images'),
            'variation-types' => Pages\ProductVariationTypes::route('/{record}/variation-types'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return
            $page->generateNavigationItems([
                EditProduct::class,
                ProductImages::class,
                ProductVariation::class
            ]);
    }

    public static function canViewAny(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole(RolesEnum::Vendor);
    }
}
