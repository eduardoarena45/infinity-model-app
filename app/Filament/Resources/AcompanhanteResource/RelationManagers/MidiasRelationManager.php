<?php

namespace App\Filament\Resources\AcompanhanteResource\RelationManagers;

use App\Models\Media;
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

    // Este formulário agora é usado apenas para a edição de um item individual, se necessário.
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('path')
                ->label('Ficheiro de Mídia')
                ->directory('galerias')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Pré-visualização')
                    ->disk('public')
                    ->width(100)
                    ->height(100)
                    ->url(fn (Media $record): string => Storage::disk('public')->url($record->path))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('status')->badge()->color(fn(string $state) => match($state) {
                    'aprovado' => 'success',
                    'rejeitado' => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('type')->label('Tipo')->badge(),
            ])
            ->headerActions([
                // --- INÍCIO DA CORREÇÃO ---
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Novas Mídias')
                    ->form([
                        Forms\Components\FileUpload::make('novas_midias')
                            ->label('Selecione as fotos ou vídeos')
                            ->multiple() // <-- A MUDANÇA PRINCIPAL ESTÁ AQUI
                            ->reorderable()
                            ->required()
                            ->directory('galerias')
                            ->acceptedFileTypes(['image/*','video/*']),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        // Pega o usuário dono deste perfil
                        $user = $livewire->ownerRecord->user;

                        // Loop para salvar cada um dos arquivos enviados
                        foreach ($data['novas_midias'] as $path) {
                            $mimeType = Storage::disk('public')->mimeType($path);
                            $type = Str::startsWith($mimeType, 'image') ? 'image' : 'video';

                            // Cria um novo registro de mídia para cada arquivo
                            Media::create([
                                'user_id' => $user->id,
                                'path' => $path,
                                'type' => $type,
                                'status' => 'aprovado', // Mídias adicionadas pelo admin já entram como aprovadas
                            ]);
                        }
                    })
                // --- FIM DA CORREÇÃO ---
            ])
            ->actions([
                Tables\Actions\Action::make('aprovar')->label('Aprovar')->icon('heroicon-o-check-circle')->color('success')->requiresConfirmation()
                    ->action(function (Media $record) {
                        $record->update(['status' => 'aprovado']);
                    })->visible(fn (Media $record): bool => $record->status !== 'aprovado'),

                Tables\Actions\Action::make('rejeitar')->label('Rejeitar')->icon('heroicon-o-x-circle')->color('danger')->requiresConfirmation()
                    ->action(fn (Media $record) => $record->update(['status' => 'rejeitado']))
                    ->visible(fn (Media $record): bool => $record->status !== 'rejeitado'),

                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
