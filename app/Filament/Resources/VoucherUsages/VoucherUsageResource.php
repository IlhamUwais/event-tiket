<?php

namespace App\Filament\Resources\VoucherUsages;

use App\Filament\Resources\VoucherUsages\Pages\ListVoucherUsages;
use App\Filament\Resources\VoucherUsages\Schemas\VoucherUsageForm;
use App\Filament\Resources\VoucherUsages\Tables\VoucherUsagesTable;
use App\Models\voucher_usages;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VoucherUsageResource extends Resource
{
    protected static ?string $model = voucher_usages::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'id';

    protected static \UnitEnum|string|null $navigationGroup = 'Transactions';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return VoucherUsageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VoucherUsagesTable::configure($table);
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
            'index' => ListVoucherUsages::route('/'),
        ];
    }
}
