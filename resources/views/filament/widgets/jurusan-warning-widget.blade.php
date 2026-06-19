<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium mb-4">Status Absensi Per Jurusan</h2>

        <div class="space-y-3">
            @forelse($jurusanData as $data)
                <div class="flex items-center justify-between p-3 rounded-lg {{ $data['peringatan'] ? 'bg-danger-50' : 'bg-success-50' }}">
                    <div>
                        <span class="font-medium">{{ $data['nama'] }}</span>
                        <span class="ml-2 text-sm text-gray-500">
                            {{ $data['total'] }} / {{ $data['target'] }} mahasiswa
                        </span>
                    </div>
                    <div>
                        @if($data['peringatan'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-800">
                                ⚠️ Peringatan
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                ✓ Lengkap
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada data jurusan.</p>
            @endforelse
        </div>
    </x-filament::card>
</x-filament::widget>
