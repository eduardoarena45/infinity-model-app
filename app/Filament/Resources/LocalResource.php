<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocalResource\Pages;
use App\Models\Cidade; // CORRIGIDO: Agora gere o modelo Cidade
use App\Models\Estado; // Precisamos do modelo Estado para o formulário
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LocalResource extends Resource
{
    protected static ?string $model = Cidade::class; // CORRIGIDO: O modelo agora é Cidade
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Gestão do Site';
    
    // CORRIGIDO: Nomes atualizados para refletir que estamos a gerir Cidades
    protected static ?string $modelLabel = 'Cidade Atendida';
    protected static ?string $pluralModelLabel = 'Cidades Atendidas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // CORRIGIDO: Formulário para adicionar uma nova cidade
                Forms\Components\Select::make('estado_id')
                    ->label('Estado')
                    ->options(Estado::all()->pluck('nome', 'id')) // Mostra um dropdown com todos os estados
                    ->searchable()
                    ->required(),
                
                Forms\Components\TextInput::make('nome')
                    ->label('Nome da Cidade')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // CORRIGIDO: Tabela para listar as cidades
                Tables\Columns\TextColumn::make('nome')
                    ->label('Cidade')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('estado.nome') // Mostra o nome do estado através da relação
                    ->label('Estado')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), 
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ])
            ]);
    }
    
    public static function getPages(): array
    {
        // As páginas são mantidas, mas agora referem-se a Cidades
        return [
            'index' => Pages\ListLocals::route('/'),
            'create' => Pages\CreateLocal::route('/create'),
            'edit' => Pages\EditLocal::route('/{record}/edit'),
        ];
    }
}
