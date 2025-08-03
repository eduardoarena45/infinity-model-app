<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanoResource\Pages;
use App\Models\Plano;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PlanoResource extends Resource
{
    protected static ?string $model = Plano::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Gestão de Negócio';
    protected static ?string $label = 'Plano';
    protected static ?string $pluralLabel = 'Planos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Principais')
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Plano::class, 'slug', ignoreRecord: true)
                            ->helperText('Este é o link amigável do plano. Gerado automaticamente.'),

                        Forms\Components\Textarea::make('descricao')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Valores e Limites')
                    ->schema([
                        Forms\Components\TextInput::make('preco')
                            ->numeric()->prefix('R$')->required(),

                        Forms\Components\TextInput::make('limite_fotos')
                            ->numeric()->required()->helperText('Número de fotos permitidas na galeria.'),

                        Forms\Components\TextInput::make('limite_videos')
                            ->label('Limite vídeos')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->helperText('Número de vídeos permitidos na galeria.'),
                        
                        Forms\Components\TextInput::make('limite_descricao')
                            ->label('Limite de Caracteres na Descrição')
                            ->numeric()
                            ->nullable()
                            ->helperText('Deixe em branco ou 0 para ilimitado.'),

                    ])->columns(2),
                
                Forms\Components\Section::make('Funcionalidades Extras')
                    ->schema([
                        Forms\Components\Toggle::make('destaque')
                            ->label('Perfil em Destaque?')
                            ->helperText('Ative se este plano coloca o perfil no topo da página.')
                            ->required(),
                        
                        Forms\Components\Toggle::make('permite_videos')
                            ->label('Permitir Vídeos na Galeria?')
                            ->required(),

                        // --- NOVO CAMPO DE PRIORIDADE ADICIONADO AQUI ---
                        Forms\Components\TextInput::make('prioridade')
                            ->label('Prioridade de Exibição')
                            ->numeric()
                            ->required()
                            ->default(99)
                            ->helperText('Menor número = maior prioridade. Ex: 1 para Premium, 10 para Básico.'),
                    ])->columns(3), // Ajustado para 3 colunas para melhor layout
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->searchable(),
                Tables\Columns\TextColumn::make('preco')->money('BRL')->sortable(),
                
                // --- NOVA COLUNA DE PRIORIDADE ADICIONADA AQUI ---
                Tables\Columns\TextColumn::make('prioridade')
                    ->label('Prioridade')
                    ->sortable(),

                Tables\Columns\TextColumn::make('limite_fotos')->label('Limite de Fotos')->sortable(),
                Tables\Columns\TextColumn::make('limite_videos')->label('Limite de Vídeos')->sortable(),
                
                Tables\Columns\TextColumn::make('limite_descricao')
                    ->label('Limite Descrição')
                    ->sortable()
                    ->formatStateUsing(fn (?int $state) => $state ?: 'Ilimitado'),

                Tables\Columns\IconColumn::make('destaque')->label('Destaque')->boolean(),
                Tables\Columns\IconColumn::make('permite_videos')->label('Permite Vídeos')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array 
    { 
        return [
            'index' => Pages\ListPlanos::route('/'),
            'create' => Pages\CreatePlano::route('/create'),
            'edit' => Pages\EditPlano::route('/{record}/edit'),
        ]; 
    }
}
