<?php
namespace App\Filament\Resources;
use App\Filament\Resources\MidiaResource\Pages;
use App\Models\Midia;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MidiaResource extends Resource
{
    protected static ?string $model = Midia::class;
    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationGroup = 'Moderação';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('caminho_arquivo')->label('Mídia')->height(100),
                Tables\Columns\TextColumn::make('acompanhante.nome_artistico')->label('Perfil')->searchable(),
                Tables\Columns\TextColumn::make('tipo')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'pendente' => 'warning', 'aprovado' => 'success', 'rejeitado' => 'danger',
                }),
            ])
            ->filters([ Tables\Filters\SelectFilter::make('status')->options(['pendente'=>'Pendente','aprovado'=>'Aprovado','rejeitado'=>'Rejeitado'])->default('pendente') ])
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
                                $image->place($logoPath, 'bottom-right', 10, 10, 70); // Posição, offset X, offset Y, opacidade
                            }

                            $image->save($path);
                        }
                        $record->update(['status' => 'aprovado']);
                    })->visible(fn (Midia $record): bool => $record->status !== 'aprovado'),

                Tables\Actions\Action::make('rejeitar')->label('Rejeitar')->icon('heroicon-o-x-circle')->color('danger')->requiresConfirmation()
                    ->action(fn (Midia $record) => $record->update(['status' => 'rejeitado']))
                    ->visible(fn (Midia $record): bool => $record->status !== 'rejeitado'),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListMidias::route('/')];
    }
}