<x-filament-panels::page>
    <div class="space-y-6">
        <div wire:ignore class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Scanner QR Tiket</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Arahkan kamera ke QR tiket user. Kamera akan aktif otomatis.
            </p>

            <div
                x-data="ticketScanner(@this)"
                x-init="startScanner()"
                class="mt-4"
            >
                <div
                    id="reader"
                    class="min-h-[300px] overflow-hidden rounded-lg border border-dashed border-gray-300 dark:border-gray-600"
                ></div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Atau Input Manual</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Gunakan input ini jika QR rusak atau kamera bermasalah.
            </p>

            <form wire:submit.prevent="submitManualCheckin" class="mt-4 flex flex-col gap-3 md:flex-row md:items-end">
                <div class="w-full">
                    <label for="manual-ticket-code" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Kode Tiket
                    </label>
                    <input
                        id="manual-ticket-code"
                        type="text"
                        wire:model.defer="manualTicketCode"
                        placeholder="Contoh: 6SMZEKTFBF"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />
                </div>

                <button
                    type="submit"
                    class="inline-flex h-10 items-center justify-center rounded-lg bg-primary-600 px-4 text-sm font-semibold text-white transition hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    Check In
                </button>
            </form>
        </div>
    </div>

    @once
        @push('scripts')
            <script src="https://unpkg.com/html5-qrcode" defer></script>
            <script>
                function ticketScanner($wire) {
                    return {
                        scanner: null,
                        scannerStarted: false,
                        lastCode: null,
                        processing: false,

                        async startScanner() {
                            this.waitLibraryAndStart();

                            window.addEventListener('scan-ticket-reset', () => {
                                this.processing = false;
                            });
                        },

                        waitLibraryAndStart() {
                            const tryStart = async () => {
                                if (typeof Html5Qrcode === 'undefined') {
                                    setTimeout(tryStart, 200);
                                    return;
                                }

                                if (this.scannerStarted) {
                                    return;
                                }

                                this.scanner = new Html5Qrcode('reader');

                                try {
                                    await this.scanner.start(
                                        { facingMode: 'environment' },
                                        { fps: 10, qrbox: 250 },
                                        async (decodedText) => {
                                            if (this.processing) {
                                                return;
                                            }

                                            if (decodedText === this.lastCode) {
                                                return;
                                            }

                                            this.processing = true;
                                            this.lastCode = decodedText;

                                            await $wire.processCheckin(decodedText);

                                            setTimeout(() => {
                                                this.lastCode = null;
                                                this.processing = false;
                                            }, 1200);
                                        }
                                    );

                                    this.scannerStarted = true;
                                } catch (error) {
                                    console.error('Gagal mengaktifkan kamera scanner:', error);
                                }
                            };

                            tryStart();
                        }
                    };
                }
            </script>
        @endpush
    @endonce
</x-filament-panels::page>
