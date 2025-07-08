<?php
namespace App\Filament\Resources;
use App\Filament\Resources\LocalResource\Pages;
use App\Models\Local;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
class LocalResource extends Resource
{
    protected static ?string $model = Local::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Gestão do Site';
    protected static ?string $pluralModelLabel = 'Locais Atendidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('estado')->label('Estado (UF)')->required()->maxLength(2),
                Forms\Components\TextInput::make('cidade')->required()->maxLength(255),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('estado')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cidade')->searchable()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocals::route('/'),
            'create' => Pages\CreateLocal::route('/create'),
            'edit' => Pages\EditLocal::route('/{record}/edit'),
        ];
    }
}