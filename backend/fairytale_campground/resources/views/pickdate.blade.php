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
            background-color: #ffffff;
            border: 2px solid #1d4807;
        }

        .page-bg {
        background-image: url("/img/pickdate.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border: 2px solid #1d4807;
        }

        /* === FLATPICKR stylr === */
        .flatpickr-calendar {
            border: 2px solid #1d4807;
            border-radius: 12px;
            background: #ffffff;
        }

        .flatpickr-months .flatpickr-month {
            background: #1d4807;
            color: white;
        }

        .flatpickr-weekdays {
            background: #d9f2d0;
            color: white;
        }

        .flatpickr-day.today {
            background: #d9f2d0;
            border-color: #1d4807;
        }

        .flatpickr-day.selected {
            background: #1d4807 !important;
            border-color: #1d4807 !important;
            color: white;
        }

        .flatpickr-day:hover {
            background: #1d4807;
            color: white;
        }

        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: #ccc;
            cursor: not-allowed;
        }

        .navbar-custom {
        width: 100%;
        background-color: #1d4807;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        padding: 14px 0;
        z-index: 999;
        box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
        }

        /* INNER CONTAINER */
        .navbar-container {
        margin: auto;
        padding: 0 24px;

        display: flex;
        justify-content: space-between;
        align-items: center;
        }

        .navbar-logo {
        font-size: 26px;
        font-weight: 700;
        color: white;
        text-decoration: none;
        }

        .navbar-menu {
        list-style: none;
        display: flex;
        gap: 32px;
        margin: 0;
        padding: 0;
        }

        .navbar-menu a {
        color: white;
        text-decoration: none;
        font-size: 18px;
        font-weight: 500;
        transition: 0.2s;
        }

        .navbar-menu a:hover {
        color: #bbf7d0;
        }

        .navbar-auth {
        display: flex;
        gap: 16px;
        }

        .btn-outline {
        padding: 8px 16px;
        border: 2px solid white;
        border-radius: 10px;
        color: white;
        text-decoration: none;
        transition: 0.2s;
        }

        .btn-outline:hover {
        background-color: white;
        color: #1d4807;
        }

        .btn-solid {
        padding: 8px 16px;
        background-color: white;
        color: #1d4807;
        font-weight: 600;
        text-decoration: none;
        border-radius: 10px;
        transition: 0.2s;
        }

        .btn-solid:hover {
        background-color: #bbf7d0;
        }
    </style>

@endsection

@section('content')
    
    <div class="flex justify-center items-center min-h-screen px-6 page-bg">
        <div class="card p-8 rounded-2xl shadow-xl w-full max-w-lg">
            <h1 class="text-3xl font-bold text-center text-[white] mb-6">
                Pilih Tanggal Camping
            </h1>

            <form id="dateForm" method="GET" action="/api/tent/available" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-semibold text-[white]">Tanggal Check-In</label>
                    <input type="text"
                           id="checkin"
                           class="w-full mt-1 p-3 rounded-lg border border-[#1d4807] focus:ring-2 focus:ring-[#225909]"
                           placeholder="Pilih tanggal"
                           required name="tanggal_checkin">
                </div>

                <div>
                    <label class="text-sm font-semibold text-[white]">Tanggal Check-Out</label>
                    <input type="text"
                           id="checkout"
                           class="w-full mt-1 p-3 rounded-lg border border-[#1d4807] focus:ring-2 focus:ring-[#225909]"
                           placeholder="Pilih tanggal"
                           required name="tanggal_checkout">
                </div>

                <button type="submit"
                        class="w-full bg-[#1d4807] hover:bg-[#225909] text-white font-semibold p-3 rounded-xl transition shadow-lg">
                    Lihat paket dan tenda yang tersedia
                </button>
            </form>
        </div>
    </div>

    <div id="popupWarning"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white border-2 border-[#1d4807] rounded-xl p-6 w-80 shadow-xl text-center">
            <h2 class="text-lg font-bold text-[#1d4807] mb-3">Tanggal Belum Lengkap</h2>
            <p class="text-gray-700 mb-4">Silakan pilih tanggal Check-In dan Check-Out.</p>
            <button onclick="closePopup()"
                    class="px-5 py-2 bg-[#1d4807] text-white rounded-lg hover:bg-[#225909]">
                Mengerti
            </button>
        </div>
    </div>

    <script>
        // <!--- helper for availability check --->
        async function fetchAvailabilityFromBackend(checkIn, checkOut) {
            // contoh fetch ke endpoint nyata:
            // return fetch(`/api/availability?checkIn=${checkIn}&checkOut=${checkOut}`)
            //   .then(r => r.json());

            // FALLBACK SIMULASI (jika backend tidak tersedia)
            // Struktur return:
            // { available: true/false, details: { "Single": true, "Double": true, "Family": false } }
            await new Promise(r => setTimeout(r, 300)); // simulasi latency
            // contoh simulasi: Family tidak tersedia jika checkout di akhir bulan (random demo)
            const unavailableFamily = new Date(checkOut).getDate() % 2 === 0; // random rule demo
            return {
                available: !unavailableFamily,
                details: {
                    Single: true,
                    Double: true,
                    Family: !unavailableFamily
                }
            };
        }

        // fungsi umum yang dipakai di submit dan di halaman pilihan_tenda (re-usable)
        async function checkAvailability(checkIn, checkOut) {
            try {
                const resp = await fetchAvailabilityFromBackend(checkIn, checkOut);
                return resp; // object
            } catch (err) {
                console.warn("Availability check failed, fallback to assume available", err);
                return { available: true, details: { Single: true, Double: true, Family: true } };
            }
        }

        // FLATPICKR init (sama seperti milikmu)
        const checkIn = flatpickr("#checkin", {
            minDate: "today",
            dateFormat: "Y-m-d",
            onChange: function (selectedDates) {
                if (selectedDates.length > 0) {
                    const chosenDate = selectedDates[0].toISOString().split('T')[0];
                    localStorage.setItem("checkin_date", JSON.stringify(chosenDate));
                    localStorage.setItem("checkIn", JSON.stringify(chosenDate));
                    const minCheckout = new Date(selectedDates[0]);
                    minCheckout.setDate(minCheckout.getDate() + 1);
                    checkOut.set("minDate", minCheckout);
                    checkOut.setDate("");
                }
            }
        });

        const checkOut = flatpickr("#checkout", {
            dateFormat: "Y-m-d",
            onChange: function (selectedDates) {
                if (selectedDates.length > 0) {
                    const chosenDate = selectedDates[0].toISOString().split('T')[0];
                    localStorage.setItem("checkout_date", JSON.stringify(chosenDate));
                    localStorage.setItem("checkOut", JSON.stringify(chosenDate));
                }
            }
        });

        window.addEventListener("DOMContentLoaded", () => {
            const savedCheckin = JSON.parse(localStorage.getItem("checkIn") || localStorage.getItem("checkin_date") || "null");
            const savedCheckout = JSON.parse(localStorage.getItem("checkOut") || localStorage.getItem("checkout_date") || "null");

            if (savedCheckin) {
                checkIn.setDate(savedCheckin);
                const minCheckout = new Date(savedCheckin);
                minCheckout.setDate(minCheckout.getDate() + 1);
                checkOut.set("minDate", minCheckout);
            }

            if (savedCheckout) {
                checkOut.setDate(savedCheckout);
            }
        });

        function showPopup() {
            document.getElementById("popupWarning").classList.remove("hidden");
        }
        function closePopup() {
            document.getElementById("popupWarning").classList.add("hidden");
        }

        document.getElementById("dateForm").addEventListener("submit", async function (e) {
            e.preventDefault();

            const checkinValue = checkIn.input.value;
            const checkoutValue = checkOut.input.value;

            if (!checkinValue || !checkoutValue) {
                showPopup();
                return;
            }

            // simpan ke localStorage (compatible)
            localStorage.setItem("checkIn", JSON.stringify(checkinValue));
            localStorage.setItem("checkOut", JSON.stringify(checkoutValue));
            localStorage.setItem("checkin_date", JSON.stringify(checkinValue));
            localStorage.setItem("checkout_date", JSON.stringify(checkoutValue));

            // // cek ketersediaan via AJAX (frontend)
            // const availability = await checkAvailability(checkinValue, checkoutValue);
            // if (!availability.available) {
            //     // tampilkan pesan spesifik (kamu bisa custom)
            //     alert("Maaf, unit tenda tidak tersedia pada tanggal yang dipilih. Silakan pilih tanggal lain atau ubah jumlah/pilihan tenda.");
            //     return;
            // }

            // kalau available, redirect ke paket.html (atau halaman pilihan_tenda)
            window.location.href = "/package"; // atau paket.html sesuai flowmu
        });
    </script>

@endsection