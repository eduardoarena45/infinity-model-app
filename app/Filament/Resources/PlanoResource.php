<?php
namespace App\Filament\Resources;
use App\Filament\Resources\PlanoResource\Pages;
use App\Models\Plano;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
class PlanoResource extends Resource
{
    protected static ?string $model = Plano::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Gestão de Negócio';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')->required()->maxLength(255),
                Forms\Components\TextInput::make('preco')->numeric()->prefix('R$')->required(),
                Forms\Components\TextInput::make('limite_fotos')->numeric()->required(),
                Forms\Components\TextInput::make('limite_videos')->numeric()->required(),
                Forms\Components\Toggle::make('permite_destaque')->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->searchable(),
                Tables\Columns\TextColumn::make('preco')->money('BRL'),
                Tables\Columns\TextColumn::make('limite_fotos'),
                Tables\Columns\IconColumn::make('permite_destaque')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }
    public static function getPages(): array { return ['index' => Pages\ListPlanos::route('/'),'create' => Pages\CreatePlano::route('/create'),'edit' => Pages\EditPlano::route('/{record}/edit'),]; }
}