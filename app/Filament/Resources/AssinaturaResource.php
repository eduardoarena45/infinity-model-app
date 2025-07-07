<?php
namespace App\Filament\Resources;
use App\Filament\Resources\AssinaturaResource\Pages;
use App\Models\Assinatura;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
class AssinaturaResource extends Resource
{
    protected static ?string $model = Assinatura::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'Gestão de Negócio';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->required(),
                Forms\Components\Select::make('plano_id')->relationship('plano', 'nome')->required(),
                Forms\Components\DateTimePicker::make('data_expiracao')->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('plano.nome'),
                Tables\Columns\TextColumn::make('data_expiracao')->dateTime()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }
    public static function getPages(): array { return ['index' => Pages\ListAssinaturas::route('/'),'create' => Pages\CreateAssinatura::route('/create'),'edit' => Pages\EditAssinatura::route('/{record}/edit'),]; }
}