<?php
namespace App\Filament\Resources\AcompanhanteResource\RelationManagers;
use App\Models\Midia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MidiasRelationManager extends RelationManager
{
    protected static string $relationship = 'midias';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('caminho_arquivo')->label('Ficheiro de Mídia')->directory('galerias')->required()->acceptedFileTypes(['image/*','video/*']),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('caminho_arquivo')
            ->columns([
                Tables\Columns\ImageColumn::make('caminho_arquivo')->label('Pré-visualização'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn(string $state) => $state === 'aprovado' ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('tipo')->badge(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Nova Mídia')
                    ->mutateFormDataUsing(function (array $data): array {
                        $file = $data['caminho_arquivo'];
                        $data['tipo'] = Str::startsWith($file->getMimeType(), 'image') ? 'foto' : 'video';
                        $data['status'] = 'pendente';
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('aprovar')->label('Aprovar')->icon('heroicon-o-check-circle')->color('success')->requiresConfirmation()
                    ->action(function (Midia $record) {
                        if ($record->tipo === 'foto') {
                            // LÓGICA ATUALIZADA PARA USAR O LOGOTIPO
                            $manager = new ImageManager(new Driver());
                            $path = Storage::disk('public')->path($record->caminho_arquivo);
                            $image = $manager->read($path);
                            $logoPath = public_path('images/logo-watermark.png');

                            if (file_exists($logoPath)) {
                                $image->place($logoPath, 'bottom-right', 10, 10, 70);
                            }

                            $image->save($path);
                        }
                        $record->update(['status' => 'aprovado']);
                    })->visible(fn (Midia $record): bool => $record->status !== 'aprovado'),

                Tables\Actions\Action::make('rejeitar')->label('Rejeitar')->icon('heroicon-o-x-circle')->color('danger')->requiresConfirmation()
                    ->action(fn (Midia $record) => $record->update(['status' => 'rejeitado']))
                    ->visible(fn (Midia $record): bool => $record->status !== 'rejeitado'),

                Tables\Actions\DeleteAction::make(),
            ]);
    }
}