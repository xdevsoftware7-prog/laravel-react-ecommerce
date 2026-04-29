<?php

namespace App\Filament\Resources;

use App\Enums\Enums\ProductStatusEnum;
use App\Enums\RolesEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole(RolesEnum::Vendor);
    }
}
