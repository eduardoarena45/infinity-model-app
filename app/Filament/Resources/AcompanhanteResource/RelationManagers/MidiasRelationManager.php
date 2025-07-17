<?php

namespace App\Filament\Resources\AcompanhanteResource\RelationManagers;

use App\Models\Media; // CORRIGIDO: Usa o Model 'Media' com 'e'
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MidiasRelationManager extends RelationManager
{
    protected static string $relationship = 'midias';

    public function form(Form $form): Form
    {
        // Este formulário é usado para o botão 'Create'
        return $form->schema([
            Forms\Components\FileUpload::make('path') // CORRIGIDO: Usa a coluna 'path'
                ->label('Ficheiro de Mídia')
                ->directory('galerias')
                ->required()
                ->acceptedFileTypes(['image/*','video/*']),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path') // CORRIGIDO: Usa a coluna 'path'
            ->columns([
                Tables\Columns\ImageColumn::make('path') // CORRIGIDO: Usa a coluna 'path'
                    ->label('Pré-visualização')
                    ->disk('public')
                    ->width(100)
                    ->height(100)
                    // CORRIGIDO: Usa 'Media' e a coluna 'path'
                    ->url(fn (Media $record): string => Storage::disk('public')->url($record->path))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('status')->badge()->color(fn(string $state) => match($state) {
                    'aprovado' => 'success',
                    'rejeitado' => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('type')->label('Tipo')->badge(), // CORRIGIDO: Usa a coluna 'type'
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Nova Mídia')
                    // A lógica de criação foi simplificada para usar o formulário padrão
            ])
            ->actions([
                Tables\Actions\Action::make('aprovar')->label('Aprovar')->icon('heroicon-o-check-circle')->color('success')->requiresConfirmation()
                    // CORRIGIDO: Usa 'Media'
                    ->action(function (Media $record) {
                        $record->update(['status' => 'aprovado']);
                    })->visible(fn (Media $record): bool => $record->status !== 'aprovado'),

                Tables\Actions\Action::make('rejeitar')->label('Rejeitar')->icon('heroicon-o-x-circle')->color('danger')->requiresConfirmation()
                    // CORRIGIDO: Usa 'Media'
                    ->action(fn (Media $record) => $record->update(['status' => 'rejeitado']))
                    ->visible(fn (Media $record): bool => $record->status !== 'rejeitado'),

                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
