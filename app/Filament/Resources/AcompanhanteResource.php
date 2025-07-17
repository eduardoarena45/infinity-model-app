<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcompanhanteResource\Pages;
use App\Filament\Resources\AcompanhanteResource\RelationManagers;
use App\Models\Acompanhante;
use App\Models\Estado;
use App\Models\Cidade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
                        Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required()->disabledOn('edit'),
                        Forms\Components\Toggle::make('is_verified')->label('Perfil Verificado'),
                        Forms\Components\Toggle::make('is_featured')->label('Perfil em Destaque'),
                        Forms\Components\Select::make('status')
                            ->options(['pendente' => 'Pendente', 'aprovado' => 'Aprovado', 'rejeitado' => 'Rejeitado'])
                            ->required(),
                    ])->columns(4),

                Forms\Components\Section::make('Dados Públicos')
                    ->schema([
                        Forms\Components\TextInput::make('nome_artistico')->required()->maxLength(255),
                        Forms\Components\DatePicker::make('data_nascimento')->required(),
                        Forms\Components\Textarea::make('descricao')->required()->columnSpanFull(),
                        
                        Forms\Components\Select::make('estado_id')
                            ->label('Estado')
                            ->options(Estado::all()->pluck('nome', 'id'))
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('cidade_id', null))
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('cidade_id')
                            ->label('Cidade')
                            ->options(function (Get $get): Collection {
                                $estado = Estado::find($get('estado_id'));
                                if (!$estado) { return collect(); }
                                return $estado->cidades->pluck('nome', 'id');
                            })
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('whatsapp')->tel()->required(),
                        Forms\Components\TextInput::make('valor_hora')->numeric()->prefix('R$')->required(),
                        
                        Forms\Components\CheckboxList::make('servicos')
                            ->relationship('servicos', 'nome')
                            ->columns(3)
                    ])->columns(2),
                
                Forms\Components\Section::make('Mídia')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_principal_path')
                            ->label('Foto Principal')
                            ->image()->directory('perfis'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // --- LINHA CORRIGIDA ---
                // Agora aponta para a coluna do banco de dados e especifica o disco.
                Tables\Columns\ImageColumn::make('foto_principal_path')
                    ->label('Foto')
                    ->disk('public') // Diz ao Filament para procurar no disco 'public'
                    ->circular(),
                // --- FIM DA CORREÇÃO ---

                Tables\Columns\TextColumn::make('nome_artistico')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'pendente' => 'warning', 'aprovado' => 'success', 'rejeitado' => 'danger',
                }),
                Tables\Columns\IconColumn::make('is_verified')->label('Verificado')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->label('Destaque')->boolean(),
                Tables\Columns\TextColumn::make('cidade.nome')->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['pendente' => 'Pendente', 'aprovado' => 'Aprovado', 'rejeitado' => 'Rejeitado'])
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
