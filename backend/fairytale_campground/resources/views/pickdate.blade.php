@extends('index')

@section('title')
    Booking - FairyTale Campground
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

        <form id="dateForm" class="space-y-5">

            <div>
                <label class="text-sm font-semibold text-[white]">Tanggal Check-In</label>
                <input type="text" id="checkin" class="w-full mt-1 p-3 rounded-lg border border-[#1d4807] focus:ring-2 focus:ring-[#225909]" placeholder="Pilih tanggal" required>
            </div>

            <div>
                <label class="text-sm font-semibold text-[white]">Tanggal Check-Out</label>
                <input type="text" id="checkout" class="w-full mt-1 p-3 rounded-lg border border-[#1d4807] focus:ring-2 focus:ring-[#225909]" placeholder="Pilih tanggal" required>
            </div>

            <button type="submit" class="w-full bg-[#1d4807] hover:bg-[#225909] text-white font-semibold p-3 rounded-xl transition shadow-lg">
                Lihat paket dan tenda yang tersedia
            </button>
        </form>
    </div>
</div>

<div id="popupWarning" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white border-2 border-[#1d4807] rounded-xl p-6 w-80 shadow-xl text-center">
        <h2 class="text-lg font-bold text-[#1d4807] mb-3">Tanggal Belum Lengkap</h2>
        <p class="text-gray-700 mb-4">Silakan pilih tanggal Check-In dan Check-Out.</p>
        <button onclick="closePopup()" class="px-5 py-2 bg-[#1d4807] text-white rounded-lg hover:bg-[#225909]">Mengerti</button>
    </div>
</div>

<script>
    // Flatpickr init
    const checkIn = flatpickr("#checkin", {
        minDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if(selectedDates.length > 0){
                const chosenDate = selectedDates[0].toISOString().split('T')[0];
                localStorage.setItem("checkIn", chosenDate);
                const minCheckout = new Date(selectedDates[0]);
                minCheckout.setDate(minCheckout.getDate() + 1);
                checkOut.set("minDate", minCheckout);
                checkOut.setDate("");
            }
        }
    });

    const checkOut = flatpickr("#checkout", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if(selectedDates.length > 0){
                const chosenDate = selectedDates[0].toISOString().split('T')[0];
                localStorage.setItem("checkOut", chosenDate);
            }
        }
    });

    // Load dari localStorage saat reload
    window.addEventListener("DOMContentLoaded", () => {
        const savedCheckin = localStorage.getItem("checkIn");
        const savedCheckout = localStorage.getItem("checkOut");

        if(savedCheckin){
            checkIn.setDate(savedCheckin);
            const minCheckout = new Date(savedCheckin);
            minCheckout.setDate(minCheckout.getDate() + 1);
            checkOut.set("minDate", minCheckout);
        }
        if(savedCheckout){
            checkOut.setDate(savedCheckout);
        }
    });

    // Popup helper
    function showPopup(){ document.getElementById("popupWarning").classList.remove("hidden"); }
    function closePopup(){ document.getElementById("popupWarning").classList.add("hidden"); }

    // Submit form
    document.getElementById("dateForm").addEventListener("submit", function(e){
        e.preventDefault();
        const checkinValue = checkIn.input.value;
        const checkoutValue = checkOut.input.value;

        if(!checkinValue || !checkoutValue){
            showPopup();
            return;
        }

        // Simpan tanggal ke localStorage
        localStorage.setItem("checkIn", checkinValue);
        localStorage.setItem("checkOut", checkoutValue);

        // Cek login via AJAX
        fetch("{{ route('api.check-login') }}", {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.logged_in){
                // redirect ke halaman paket
                window.location.href = "{{ route('package.index') }}";
            } else {
                // redirect ke login
                window.location.href = "{{ route('test-login') }}";
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan. Silakan coba lagi.");
        });
    });
</script>

@endsection
