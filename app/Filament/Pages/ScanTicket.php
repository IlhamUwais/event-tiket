<?php

namespace App\Filament\Pages;

use App\Models\Attende;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ScanTicket extends Page
{
    protected static bool $isDiscovered = false;

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?string $navigationLabel = 'Scan Tiket';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Scan Tiket';

    protected static ?string $slug = 'scan-tiket';

    protected string $view = 'filament.pages.scan-ticket';

    public string $manualTicketCode = '';

    public function processScan(string $ticketCode): void
    {
        $this->processCheckin($ticketCode);
    }

    public function submitManualCheckin(): void
    {
        $this->processCheckin($this->manualTicketCode);
    }

    public function processCheckin(string $ticketCode): void
    {
        $ticketCode = trim($ticketCode);

        if ($ticketCode === '') {
            Notification::make()
                ->title('Kode tiket wajib diisi')
                ->danger()
                ->send();

            $this->manualTicketCode = '';
            $this->dispatch('scan-ticket-reset');

            return;
        }

        $attendee = Attende::query()
            ->with(['orderDetail.tiket.event'])
            ->where('kode_tiket', $ticketCode)
            ->first();

        if (! $attendee) {
            Notification::make()
                ->title('Tiket tidak ditemukan')
                ->body("Kode tiket {$ticketCode} tidak ada di sistem.")
                ->danger()
                ->send();

            $this->manualTicketCode = '';
            $this->dispatch('scan-ticket-reset');

            return;
        }

        if ($attendee->status === 'sudah') {
            Notification::make()
                ->title('Tiket sudah digunakan')
                ->body("Kode tiket {$ticketCode} sudah check-in sebelumnya.")
                ->warning()
                ->send();

            $this->manualTicketCode = '';
            $this->dispatch('scan-ticket-reset');

            return;
        }

        $attendee->update([
            'status' => 'sudah',
            'waktu_checkin' => now(),
        ]);

        Notification::make()
            ->title('Check-in berhasil')
            ->body("Kode tiket {$ticketCode} berhasil diproses.")
            ->success()
            ->send();

        $this->manualTicketCode = '';
        $this->dispatch('scan-ticket-reset');
    }
}
