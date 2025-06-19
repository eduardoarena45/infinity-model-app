<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcompanhanteResource\Pages;
use App\Filament\Resources\AcompanhanteResource\RelationManagers;
use App\Models\Acompanhante;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcompanhanteResource extends Resource
{
    protected static ?string $model = Acompanhante::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Campo para vincular a um usuário já existente.
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                // Campo de upload para a foto principal
                Forms\Components\FileUpload::make('imagem_principal_url')
                    ->label('Foto Principal')
                    ->image() // Especifica que o upload é de uma imagem
                    ->imageEditor() // Adiciona um editor básico (cortar, girar)
                    ->directory('perfis') // Salva as imagens na pasta 'storage/app/public/perfis'
                    ->required()
                    ->columnSpanFull(), // Faz o campo ocupar a largura total

                Forms\Components\TextInput::make('nome_artistico')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->required(),
                Forms\Components\Textarea::make('descricao_curta')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cidade')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('estado')
                    ->required()
                    ->maxLength(2),
                Forms\Components\TextInput::make('whatsapp')
                    ->tel() // Tipo de campo para telefone
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('valor_hora')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Coluna para exibir a imagem de perfil
                Tables\Columns\ImageColumn::make('imagem_principal_url')
                    ->label('Foto')
                    ->circular(), // Mostra a imagem em formato circular

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuária')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome_artistico')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cidade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('idade') // Usando o atributo mágico!
                    ->label('Idade')
                    ->badge(),
                Tables\Columns\TextColumn::make('valor_hora')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListAcompanhantes::route('/'),
            'create' => Pages\CreateAcompanhante::route('/create'),
            'edit' => Pages\EditAcompanhante::route('/{record}/edit'),
        ];
    }
}
