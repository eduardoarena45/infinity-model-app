<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvaliacaoResource\Pages;
use App\Models\Avaliacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

class AvaliacaoResource extends Resource
{
    protected static ?string $model = Avaliacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Moderação';
    protected static ?string $pluralLabel = 'Avaliações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('acompanhante_id')
                    ->relationship('acompanhante', 'nome_artistico')
                    ->disabled(),
                Forms\Components\TextInput::make('nome_avaliador')
                    ->disabled(),
                Forms\Components\TextInput::make('nota')
                    ->disabled(),
                Forms\Components\Textarea::make('comentario')
                    ->columnSpanFull()
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pendente' => 'Pendente',
                        'aprovado' => 'Aprovado',
                        'rejeitado' => 'Rejeitado',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('acompanhante.nome_artistico')
                    ->label('Acompanhante')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome_avaliador')
                    ->label('Avaliador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nota')
                    ->icon('heroicon-s-star')
                    ->color('warning'),
                Tables\Columns\TextColumn::make('comentario')
                    ->limit(40)
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendente' => 'warning',
                        'aprovado' => 'success',
                        'rejeitado' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pendente' => 'Pendente',
                        'aprovado' => 'Aprovado',
                        'rejeitado' => 'Rejeitado',
                    ])
                    ->default('pendente'),
            ])
            ->actions([
                Action::make('aprovar')
                    ->label('Aprovar')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->action(fn (Avaliacao $record) => $record->update(['status' => 'aprovado']))
                    ->requiresConfirmation()
                    ->visible(fn (Avaliacao $record): bool => $record->status !== 'aprovado'),
                
                Action::make('rejeitar')
                    ->label('Rejeitar')
                    ->icon('heroicon-s-x-circle')
                    ->color('danger')
                    ->action(fn (Avaliacao $record) => $record->update(['status' => 'rejeitado']))
                    ->requiresConfirmation()
                    ->visible(fn (Avaliacao $record): bool => $record->status !== 'rejeitado'),
                
                Tables\Actions\ViewAction::make(),
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
        return [];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvaliacaos::route('/'),
            'create' => Pages\CreateAvaliacao::route('/create'),
            'edit' => Pages\EditAvaliacao::route('/{record}/edit'),
        ];
    }    

    // =======================================================
    // === NOVOS MÉTODOS PARA O CONTADOR ADICIONADOS AQUI ===
    // =======================================================
    
    public static function getNavigationBadge(): ?string
    {
        // Conta o número de avaliações com o status 'pendente'
        return static::getModel()::where('status', 'pendente')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        // Define a cor do contador como 'warning' (amarelo/laranja)
        return 'warning';
    }
}
