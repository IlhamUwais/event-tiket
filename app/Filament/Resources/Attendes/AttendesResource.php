<?php

namespace App\Filament\Resources\Attendes;

use App\Filament\Resources\Attendes\Pages\CreateAttendes;
use App\Filament\Resources\Attendes\Pages\EditAttendes;
use App\Filament\Resources\Attendes\Pages\ListAttendes;
use App\Filament\Resources\Attendes\Schemas\AttendesForm;
use App\Filament\Resources\Attendes\Tables\AttendesTable;
use App\Models\Attende;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendesResource extends Resource
{
    protected static ?string $model = Attende::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static ?string $recordTitleAttribute = 'kode_tiket';

    protected static \UnitEnum|string|null $navigationGroup = 'Transactions';

    public static function form(Schema $schema): Schema
    {
        return AttendesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendes::route('/'),
            'create' => CreateAttendes::route('/create'),
            'edit' => EditAttendes::route('/{record}/edit'),
        ];
    }
}
