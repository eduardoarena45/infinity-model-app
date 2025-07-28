<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssinaturaResource\Pages;
use App\Models\Assinatura;
use App\Models\User;
use App\Notifications\PlanoAprovadoNotification;
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
                Forms\Components\Section::make('Detalhes da Assinatura')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Utilizadora')
                            ->disabled()
                            ->required(),
                        
                        Forms\Components\Select::make('plano_id')
                            ->relationship('plano', 'nome')
                            ->label('Plano Escolhido')
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                if ($state) {
                                    $plano = \App\Models\Plano::find($state);
                                    if ($plano && $plano->preco > 0) {
                                        $set('status', 'ativa');
                                        $set('data_inicio', now());
                                        $set('data_fim', now()->addDays(30));
                                    }
                                }
                            }),
                    ])->columns(2),

                Forms\Components\Section::make('Gestão do Administrador')
                    ->schema([
                        // CAMPO DE STATUS COM A LÓGICA DE NOTIFICAÇÃO
                        Forms\Components\Select::make('status')
                            ->options([
                                'aguardando_pagamento' => 'Aguardando Pagamento',
                                'ativa' => 'Ativa',
                                'vencida' => 'Vencida',
                                'cancelada' => 'Cancelada',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $record) {
                                // Verifica se o novo estado é 'ativa'
                                if ($state === 'ativa' && $record) {
                                    $user = User::find($record->user_id);
                                    $plano = $record->plano; // Pega o plano da assinatura
                                    if ($user && $plano) {
                                        // Envia a notificação para o utilizador
                                        $user->notify(new PlanoAprovadoNotification($plano));
                                    }
                                }
                            }),
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

    // =======================================================
    // === NOVOS MÉTODOS PARA O CONTADOR ADICIONADOS AQUI ===
    // =======================================================

    public static function getNavigationBadge(): ?string
    {
        // Conta o número de assinaturas com o status 'aguardando_pagamento'
        return static::getModel()::where('status', 'aguardando_pagamento')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        // Define a cor do contador como 'warning' (amarelo/laranja)
        return 'warning';
    }
}
