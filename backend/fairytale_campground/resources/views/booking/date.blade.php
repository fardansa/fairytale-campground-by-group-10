@extends('index')

@section('title')
    Pilih Tanggal - FairyTale Campground
@endsection

@section('custom_css')
<style>
    body {
        background-color: #f4f8f3;
    }
    .card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(5px);
        border: 2px solid #1d4807;
    }
    .page-bg {
        background-image: url("/img/pickdate.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    /* Flatpickr styles */
    .flatpickr-calendar {
        border: 2px solid #1d4807;
        border-radius: 12px;
        background: #ffffff;
    }
    .flatpickr-months .flatpickr-month { background: #1d4807; color: white; }
    .flatpickr-weekdays { background: #d9f2d0; color: white; }
    .flatpickr-day.today { background: #d9f2d0; border-color: #1d4807; }
    .flatpickr-day.selected { background: #1d4807 !important; border-color: #1d4807 !important; color: white; }
    .flatpickr-day:hover { background: #1d4807; color: white; }
    .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover { color: #ccc; cursor: not-allowed; }
</style>
@endsection

@section('content')

<div class="flex justify-center items-center min-h-screen px-6 page-bg">
    <div class="card p-8 rounded-2xl shadow-xl w-full max-w-lg">
        <h1 class="text-3xl font-bold text-center text-[white] mb-6">
            Pilih Tanggal Camping
        </h1>

        <form method="POST" action="{{ route('booking.date.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="text-sm font-semibold text-[white]">Tanggal Check-In</label>
                <input type="text" name="checkin" id="checkin" class="w-full mt-1 p-3 rounded-lg border border-[#1d4807] focus:ring-2 focus:ring-[#225909]" placeholder="Pilih tanggal" required value="{{ old('checkin', session('checkin')) }}">
                @error('checkin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-[white]">Tanggal Check-Out</label>
                <input type="text" name="checkout" id="checkout" class="w-full mt-1 p-3 rounded-lg border border-[#1d4807] focus:ring-2 focus:ring-[#225909]" placeholder="Pilih tanggal" required value="{{ old('checkout', session('checkout')) }}">
                @error('checkout') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-[#1d4807] hover:bg-[#225909] text-white font-semibold p-3 rounded-xl transition shadow-lg">
                Lanjut lihat paket dan tenda yang tersedia
            </button>
        </form>
    </div>
</div>

<script>
    // Inisialisasi Flatpickr
    const checkIn = flatpickr("#checkin", {
        minDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if(selectedDates.length > 0){
                const minCheckout = new Date(selectedDates[0]);
                minCheckout.setDate(minCheckout.getDate() + 1);
                checkOut.set("minDate", minCheckout);
            }
        }
    });

    const checkOut = flatpickr("#checkout", {
        dateFormat: "Y-m-d",
    });

    // Set minDate checkout jika checkin sudah ada (session value)
    @if(session('checkin'))
        const sessionCheckin = new Date("{{ session('checkin') }}");
        const minCheckout = new Date(sessionCheckin);
        minCheckout.setDate(minCheckout.getDate() + 1);
        checkOut.set("minDate", minCheckout);
    @endif
</script>

@endsection
