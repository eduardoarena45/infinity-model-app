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
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AcompanhanteResource extends Resource
{
    protected static ?string $model = Acompanhante::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Gestão de Perfis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Gestão e Status')
                    ->schema([
                        Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
                        Forms\Components\Toggle::make('is_verified')->label('Perfil Verificado'),
                        Forms\Components\Toggle::make('is_featured')->label('Perfil em Destaque'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pendente' => 'Pendente',
                                'aprovado' => 'Aprovado',
                                'rejeitado' => 'Rejeitado',
                            ])
                            ->required(),
                    ])->columns(4),

                Forms\Components\Section::make('Dados Públicos')
                    ->schema([
                        Forms\Components\TextInput::make('nome_artistico')->required()->maxLength(255),
                        Forms\Components\DatePicker::make('data_nascimento')->required(),
                        Forms\Components\Textarea::make('descricao_curta')->required()->columnSpanFull(),
                        Forms\Components\TextInput::make('cidade')->required(),
                        Forms\Components\TextInput::make('estado')->required()->maxLength(2),
                        Forms\Components\TextInput::make('whatsapp')->tel()->required(),
                        Forms\Components\TextInput::make('valor_hora')->numeric()->prefix('R$')->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Mídia')
                    ->schema([
                        Forms\Components\FileUpload::make('imagem_principal_url')
                            ->label('Foto Principal')
                            ->image()->imageEditor()->directory('perfis')->required()
                            ->saveUploadedFileUsing(function ($file) {
                                $path = $file->store('perfis', 'public');
                                $manager = new ImageManager(new Driver());
                                $imagePath = Storage::disk('public')->path($path);
                                $image = $manager->read($imagePath);
                                $logoPath = public_path('images/logo-watermark.png');
                                if (file_exists($logoPath)) {
                                    $image->place($logoPath, 'bottom-right', 10, 10, 70);
                                }
                                $image->save($imagePath);
                                return $path;
                            }),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagem_principal_url')->label('Foto')->circular(),
                Tables\Columns\TextColumn::make('nome_artistico')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'pendente' => 'warning', 'aprovado' => 'success', 'rejeitado' => 'danger',
                }),
                Tables\Columns\IconColumn::make('is_verified')->label('Verificado')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->label('Destaque')->boolean(),
                Tables\Columns\TextColumn::make('cidade')->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['pendente' => 'Pendente', 'aprovado' => 'Aprovado', 'rejeitado' => 'Rejeitado'])
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MidiasRelationManager::class,
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
