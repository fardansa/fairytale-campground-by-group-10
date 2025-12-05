@extends('index')

@section('title')
    Pilih Tenda - FairyTale Campground
@endsection

@section('content')
<a href="{{ route('booking.paket') }}" class="btn-back mt-20 mb-3">← Kembali ke Pilih Paket</a>

<div class="container mx-auto py-8 px-4">
    {{-- Tabs Paket --}}
    <div class="mb-6">
        <ul class="flex border-b border-gray-300">
            @foreach($paketList as $paket)
                <li class="mr-2">
                    <button class="tab-link py-2 px-4 font-semibold rounded-t {{ $loop->first ? 'bg-white border-t border-l border-r shadow' : 'bg-gray-100 hover:bg-gray-200' }}" data-tab="paket-{{ $paket->paket_id }}">
                        {{ ucfirst($paket->nama_paket) }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <form id="form-tent" method="POST" action="{{ route('booking.selectTent') }}">
        @csrf
        <input type="hidden" name="tent_id" id="selected-tents" value="{{ implode(',', $selectedTents ?? []) }}">

        {{-- Legend --}}
        <div class="mb-4 flex flex-wrap gap-4 text-sm">
            <span class="px-3 py-1 rounded text-white bg-green-600">Tersedia</span>
            <span class="px-3 py-1 rounded text-white bg-red-600">Tidak tersedia</span>
            <span class="px-3 py-1 rounded text-white bg-blue-600">Dipilih</span>
        </div>

        {{-- Tab Content --}}
        <div>
            @foreach($paketList as $paket)
                @php
                    $paketColor = match($paket->nama_paket) {
                        'single' => 'bg-green-600',
                        'double' => 'bg-purple-600',
                        'family' => 'bg-amber-500',
                        default => 'bg-gray-500'
                    };
                @endphp

                <div class="tab-content {{ $loop->first ? '' : 'hidden' }}" id="paket-{{ $paket->paket_id }}">
                    <div class="grid grid-cols-6 gap-3">
                        @foreach($tendaList->where('paket_id', $paket->paket_id) as $tenda)
                            @php
                                $tentColor = $tenda->available ? $paketColor : 'bg-red-600 cursor-not-allowed';
                                $isSelected = in_array($tenda->tent_id, $selectedTents ?? []);
                                if($isSelected) $tentColor = 'bg-blue-600';
                            @endphp
                            <div class="tent-box {{ $tentColor }} text-white text-center py-2 rounded cursor-pointer shadow hover:opacity-90"
                                 data-tent-id="{{ $tenda->tent_id }}"
                                 data-paket-name="{{ $paket->nama_paket }}">
                                {{ $tenda->nomor_tent }}
                            </div>
                        @endforeach
                    </div>

                    {{-- Summary --}}
                    <div class="mt-3 text-sm">
                        <strong>Dipilih:</strong>
                        <span id="selected-summary-{{ $paket->paket_id }}">-</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Submit --}}
        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('booking.date') }}" class="px-6 py-2 bg-gray-300 text-gray-800 font-semibold rounded hover:bg-gray-400 shadow">
                ← Kembali
            </a>
            <button type="button" id="submit-btn" class="px-6 py-2 bg-[#1d4807] text-white font-semibold rounded hover:bg-green-700 shadow">
                Lanjut ke Ringkasan →
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil dari session (Blade meng-inject)
    let selectedTents = @json($selectedTents ?? []);

    // Tab switching
    document.querySelectorAll('.tab-link').forEach(link => {
        link.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');

            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('bg-white','border-t','border-l','border-r','shadow'));
            this.classList.add('bg-white','border-t','border-l','border-r','shadow');
        });
    });

    // Tent selection
    document.querySelectorAll('.tent-box').forEach(box => {
        box.addEventListener('click', function() {
            if (this.classList.contains('bg-red-600')) return;

            const tentId = this.dataset.tentId;
            const paket = this.dataset.paketName;

            let paketColors = { single: 'bg-green-600', double: 'bg-purple-600', family: 'bg-amber-500' };

            if (selectedTents.includes(tentId)) {
                // deselect
                selectedTents = selectedTents.filter(id => id != tentId);
                this.classList.remove('bg-blue-600');
                this.classList.add(paketColors[paket]);
            } else {
                // select
                selectedTents.push(tentId);
                this.classList.remove('bg-green-600','bg-purple-600','bg-amber-500');
                this.classList.add('bg-blue-600');
            }

            // Update summary per paket
            let summaryMap = {};
            document.querySelectorAll('.tent-box').forEach(tb => {
                const pkg = tb.dataset.paketName;
                if (!summaryMap[pkg]) summaryMap[pkg] = [];
                if (selectedTents.includes(tb.dataset.tentId)) summaryMap[pkg].push(tb.textContent);
            });
            @foreach($paketList as $paket)
                document.getElementById('selected-summary-{{ $paket->paket_id }}').innerText = summaryMap['{{ $paket->nama_paket }}']?.join(', ') || '-';
            @endforeach

            document.getElementById('selected-tents').value = selectedTents.join(',');
        });
    });

    // Inisialisasi summary & warna tenda dari session
    let summaryMap = {};
    document.querySelectorAll('.tent-box').forEach(tb => {
        const pkg = tb.dataset.paketName;
        if (!summaryMap[pkg]) summaryMap[pkg] = [];
        if (selectedTents.includes(tb.dataset.tentId)) {
            summaryMap[pkg].push(tb.textContent);
            tb.classList.remove('bg-green-600','bg-purple-600','bg-amber-500');
            tb.classList.add('bg-blue-600');
        }
    });
    @foreach($paketList as $paket)
        document.getElementById('selected-summary-{{ $paket->paket_id }}').innerText = summaryMap['{{ $paket->nama_paket }}']?.join(', ') || '-';
    @endforeach

    // Submit
    document.getElementById('submit-btn').addEventListener('click', function() {
        if (!selectedTents.length) {
            alert('Silakan pilih minimal 1 tenda');
            return;
        }
        document.getElementById('form-tent').submit();
    });
});
</script>
@endsection
