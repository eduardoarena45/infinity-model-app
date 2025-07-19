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

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $navigationGroup = 'Gestão de Negócio';
    protected static ?string $modelLabel = 'Assinatura';
    protected static ?string $pluralModelLabel = 'Assinaturas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Secção para identificar a assinatura
                Forms\Components\Section::make('Detalhes da Assinatura')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Utilizadora')
                            ->disabled()
                            ->required(),
                        
                        // --- INÍCIO DA CORREÇÃO AUTOMÁTICA ---
                        Forms\Components\Select::make('plano_id')
                            ->relationship('plano', 'nome')
                            ->label('Plano Escolhido')
                            ->searchable()
                            ->required()
                            ->live() // Reage a mudanças em tempo real
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                // Esta função é executada assim que um novo plano é selecionado.
                                // $state é o ID do novo plano.
                                if ($state) {
                                    $plano = \App\Models\Plano::find($state);
                                    // Se o plano selecionado não for gratuito (preço > 0),
                                    // ele ativa a assinatura automaticamente.
                                    if ($plano && $plano->preco > 0) {
                                        $set('status', 'ativa'); // Define o status para 'ativa'
                                        $set('data_inicio', now()); // Define a data de início para agora
                                        $set('data_fim', now()->addDays(30)); // Define a data de fim para 30 dias
                                    }
                                }
                            }),
                        // --- FIM DA CORREÇÃO AUTOMÁTICA ---

                    ])->columns(2),

                // Secção para a sua gestão manual
                Forms\Components\Section::make('Gestão do Administrador')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'aguardando_pagamento' => 'Aguardando Pagamento',
                                'ativa' => 'Ativa',
                                'vencida' => 'Vencida',
                                'cancelada' => 'Cancelada',
                            ])
                            ->required(),
                        Forms\Components\DateTimePicker::make('data_inicio')
                            ->label('Início da Assinatura')
                            ->required(),
                        Forms\Components\DateTimePicker::make('data_fim')
                            ->label('Fim da Assinatura')
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilizadora')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plano.nome')
                    ->label('Plano')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aguardando_pagamento' => 'warning',
                        'ativa' => 'success',
                        'vencida' => 'danger',
                        'cancelada' => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_fim')
                    ->label('Vence em')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Atualização')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aguardando_pagamento' => 'Aguardando Pagamento',
                        'ativa' => 'Ativa',
                        'vencida' => 'Vencida',
                        'cancelada' => 'Cancelada',
                    ])
                    ->default('aguardando_pagamento'),
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
    
    public static function getRelations(): array
    {
        return [];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssinaturas::route('/'),
            'create' => Pages\CreateAssinatura::route('/create'),
            'edit' => Pages\EditAssinatura::route('/{record}/edit'),
        ];
    }    
}