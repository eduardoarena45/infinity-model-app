<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MidiaResource\Pages;
use App\Models\Media; // CORRIGIDO: Usa o Model 'Media' com 'e'
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MidiaResource extends Resource
{
    protected static ?string $model = Media::class; // CORRIGIDO
    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationGroup = 'Moderação';
    protected static ?string $label = 'Mídia'; // Nome singular
    protected static ?string $pluralLabel = 'Mídias'; // Nome plural

    // O formulário não é necessário aqui, pois não editamos mídias individualmente
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // CORRIGIDO: Usa a coluna 'path' e o Storage para exibir
                Tables\Columns\ImageColumn::make('path')
                    ->label('Mídia')
                    ->disk('public')
                    ->height(100),

                // CORRIGIDO: Acessa o nome através da relação user->acompanhante
                Tables\Columns\TextColumn::make('user.acompanhante.nome_artistico')
                    ->label('Perfil')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')->label('Tipo')->badge(),

                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'pendente' => 'warning', 'aprovado' => 'success', 'rejeitado' => 'danger',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pendente'=>'Pendente','aprovado'=>'Aprovado','rejeitado'=>'Rejeitado'])
                    ->default('pendente')
            ])
            ->actions([
                Tables\Actions\Action::make('aprovar')->label('Aprovar')->icon('heroicon-o-check-circle')->color('success')->requiresConfirmation()
                    ->action(function (Media $record) { // CORRIGIDO: Usa 'Media'
                        if ($record->type === 'image') { // CORRIGIDO: Usa 'image'
                            $manager = new ImageManager(new Driver());
                            $path = Storage::disk('public')->path($record->path); // CORRIGIDO: Usa 'path'
                            $image = $manager->read($path);
                            $logoPath = public_path('images/logo-watermark.png');

                            if (file_exists($logoPath)) {
                                $image->place($logoPath, 'bottom-right', 10, 10, 70);
                            }
                            $image->save($path);
                        }
                        $record->update(['status' => 'aprovado']);
                    })->visible(fn (Media $record): bool => $record->status !== 'aprovado'), // CORRIGIDO: Usa 'Media'

                Tables\Actions\Action::make('rejeitar')->label('Rejeitar')->icon('heroicon-o-x-circle')->color('danger')->requiresConfirmation()
                    ->action(fn (Media $record) => $record->update(['status' => 'rejeitado'])) // CORRIGIDO: Usa 'Media'
                    ->visible(fn (Media $record): bool => $record->status !== 'rejeitado'), // CORRIGIDO: Usa 'Media'
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                     Tables\Actions\BulkAction::make('aprovar_em_massa')
                        ->label('Aprovar Selecionados')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(function (Media $record) {
                                if ($record->status === 'pendente' && $record->type === 'image') {
                                    $manager = new ImageManager(new Driver());
                                    $path = Storage::disk('public')->path($record->path);
                                    $image = $manager->read($path);
                                    $logoPath = public_path('images/logo-watermark.png');
                                    if (file_exists($logoPath)) {
                                        $image->place($logoPath, 'bottom-right', 10, 10, 70);
                                    }
                                    $image->save($path);
                                }
                                $record->update(['status' => 'aprovado']);
                            });
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return ['index' => Pages\ListMidias::route('/')];
    }    
}
