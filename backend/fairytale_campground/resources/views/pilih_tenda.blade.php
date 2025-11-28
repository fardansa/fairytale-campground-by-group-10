@extends('index')

@section('title')

@section('content')

    <a href="javascript:history.back()" class="btn-back"><- Kembali</a>

    <div class="d-flex justify-content-center align-items-center flex-column" style="min-height: 80vh;">

        <div class="text-center mb-3">
            <h2>Pilih Tenda Anda</h2>
            <p class="text-muted">Silakan pilih nomor tenda yang tersedia.</p>
        </div>

        <div style="width: 100%; max-width: 450px;" class="d-flex flex-column align-items-center px-3">

            {{-- Single Tent --}}
            <div class="card w-100 mb-3 shadow-sm">
                <div class="card-body">
                    <label class="form-label fw-bold">Single Tent</label>
                    <div class="input-group">
                        <select class="form-select" id="select1">
                            <option selected disabled value="">Pilih Nomor Tenda</option>
                            <option value="Single Tent - 01">Single Tent - 01</option>
                            <option value="Single Tent - 03">Single Tent - 03</option>
                            <option value="Single Tent - 10">Single Tent - 10</option>
                        </select>
                        <button class="btn btn-success" onclick="addItem('select1','list1', 'single')">Add</button>
                    </div>
                    <ul class="mt-2 list-group list-group-flush" id="list1"></ul>
                </div>
            </div>

            {{-- Double Tent --}}
            <div class="card w-100 mb-3 shadow-sm">
                <div class="card-body">
                    <label class="form-label fw-bold">Double Tent</label>
                    <div class="input-group">
                        <select class="form-select" id="select2">
                            <option selected disabled value="">Pilih Nomor Tenda</option>
                            <option value="Double Tent - 03">Double Tent - 03</option>
                            <option value="Double Tent - 04">Double Tent - 04</option>
                            <option value="Double Tent - 05">Double Tent - 05</option>
                        </select>
                        <button class="btn btn-success" onclick="addItem('select2','list2', 'double')">Add</button>
                    </div>
                    <ul class="mt-2 list-group list-group-flush" id="list2"></ul>
                </div>
            </div>

            {{-- Family Tent --}}
            <div class="card w-100 mb-3 shadow-sm">
                <div class="card-body">
                    <label class="form-label fw-bold">Family Tent</label>
                    <div class="input-group">
                        <select class="form-select" id="select3">
                            <option selected disabled value="">Pilih Nomor Tenda</option>
                            <option value="Family Tent - 02">Family Tent - 02</option>
                            <option value="Family Tent - 09">Family Tent - 09</option>
                            <option value="Family Tent - 10">Family Tent - 10</option>
                        </select>
                        <button class="btn btn-success" onclick="addItem('select3','list3', 'family')">Add</button>
                    </div>
                    <ul class="mt-2 list-group list-group-flush" id="list3"></ul>
                </div>
            </div>

            <div class="d-grid gap-2 w-100 mt-3 mb-5">
                <a class="btn btn-warning keranjang-btn py-2 fw-bold" href="/hasil" role="button">
                    Lihat Keranjang & Lanjut
                </a>
                <button class="btn btn-outline-danger btn-sm" onclick="resetSelection()">Reset Pilihan</button>
            </div>

        </div>
    </div>

    {{-- Script Availability --}}
    <script>
        function readDatesFromStorage() {
            const checkIn = JSON.parse(localStorage.getItem("checkIn") || localStorage.getItem("checkin_date") || "null");
            const checkOut = JSON.parse(localStorage.getItem("checkOut") || localStorage.getItem("checkout_date") || "null");
            return { checkIn, checkOut };
        }

        async function fetchAvailabilityFromBackend(checkIn, checkOut) {
            await new Promise(r => setTimeout(r, 200));
            const d = new Date(checkIn);
            const odd = !!(d.getDate() % 2);
            return {
                available: true,
                details: {
                    Single: {
                        "Single Tent - 01": true,
                        "Single Tent - 03": !odd,
                        "Single Tent - 10": true
                    },
                    Double: {
                        "Double Tent - 03": true,
                        "Double Tent - 04": true,
                        "Double Tent - 05": true
                    },
                    Family: {
                        "Family Tent - 02": true,
                        "Family Tent - 09": odd,
                        "Family Tent - 10": true
                    }
                }
            };
        }

        async function applyAvailabilityToOptions() {
            const { checkIn, checkOut } = readDatesFromStorage();
            if (!checkIn || !checkOut) {
                document.querySelector(".keranjang-btn").classList.add("disabled");
                document.querySelector(".keranjang-btn").setAttribute("title", "Pilih tanggal dulu di halaman Booking");
                return;
            }

            const resp = await fetchAvailabilityFromBackend(checkIn, checkOut);
            if (resp && resp.details) {
                Object.keys(resp.details).forEach(cat => {
                    const map = resp.details[cat];
                    Object.keys(map).forEach(optVal => {
                        const option = document.querySelector(`option[value="${optVal}"]`);
                        if (option) {
                            option.disabled = !map[optVal];
                            if (!map[optVal]) option.textContent += " (Tidak tersedia)";
                        }
                    });
                });

                const anyEnabled = Array
                    .from(document.querySelectorAll("select.form-select option"))
                    .some(o => !o.disabled && o.value !== "");

                const proceedBtn = document.querySelector(".keranjang-btn");
                if (!anyEnabled) {
                    proceedBtn.classList.add("disabled");
                    proceedBtn.setAttribute("title", "Tidak ada tenda tersedia pada tanggal terpilih");
                } else {
                    proceedBtn.classList.remove("disabled");
                    proceedBtn.removeAttribute("title");
                }
            }
        }

        window.addEventListener("DOMContentLoaded", applyAvailabilityToOptions);

        function addItem(selectId, listId, category) {
            const select = document.getElementById(selectId);
            const list = document.getElementById(listId);
            const value = select.value;

            if (value && value !== "" && !select.options[select.selectedIndex].disabled) {

                const STORAGE_KEY = "tendaDipilih";
                const stored = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{"single":[],"double":[],"family":[]}');
                if (!stored[category]) {
                    stored[category] = [];
                }

                if (stored[category].includes(value)) {
                    alert("Tenda ini sudah Anda pilih!");
                    return;
                }

                const li = document.createElement('li');
                li.className = "list-group-item d-flex justify-content-between align-items-center";
                li.innerText = value;
                const badge = document.createElement("span");
                badge.className = "badge bg-success rounded-pill";
                badge.innerText = "✓";
                li.appendChild(badge);
                list.appendChild(li);

                stored[category].push(value);
                localStorage.setItem(STORAGE_KEY, JSON.stringify(stored));
            } else {
                alert("Silakan pilih nomor tenda yang tersedia terlebih dahulu.");
            }
        }

        function resetSelection() {
            if (confirm("Apakah Anda yakin ingin menghapus semua pilihan?")) {
                localStorage.setItem("tendaDipilih", JSON.stringify({ single: [], double: [], family: [] }));
                location.reload();
            }
        }
    </script>

@endsection
