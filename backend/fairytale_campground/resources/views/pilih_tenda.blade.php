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
        const API_URL = "/api/tent/available";
        const STORAGE_KEY = "tendaDipilih";

        localStorage.removeItem(STORAGE_KEY);


        /*ambil tanggal*/
        function readDatesFromStorage() {
            let checkInRaw = localStorage.getItem("checkIn") || localStorage.getItem("checkin_date");
            let checkOutRaw = localStorage.getItem("checkOut") || localStorage.getItem("checkout_date");

            // parse JSON, kalo gada ya pakai string biasa
            let checkIn, checkOut;

            try {
                checkIn = JSON.parse(checkInRaw);
            } catch {
                checkIn = checkInRaw;
            }

            try {
                checkOut = JSON.parse(checkOutRaw);
            } catch {
                checkOut = checkOutRaw;
            }

            return { checkIn, checkOut };
        }

        /*fetch booking*/
        async function loadTentsFromAPI() {
            const { checkIn, checkOut } = readDatesFromStorage();
        
            if (!checkIn || !checkOut) {
                alert("Silakan pilih tanggal booking terlebih dahulu!");
                document.querySelector(".keranjang-btn").classList.add("disabled");
                return;
            }
        
            const url = `${API_URL}?tanggal_checkin=${checkIn}&tanggal_checkout=${checkOut}`;
        
            try {
                const res = await fetch(url);
                const json = await res.json();
            
                if (!json.data) {
                    alert("Data tenda tidak ditemukan");
                    return;
                }
            
                renderSelectOptions(json.data);
            
            } catch (err) {
                console.error(err);
                alert("Gagal mengambil data tenda!");
            }
        }

        /*update list saat select*/
        function renderSelectOptions(data) {
            const selectSingle = document.getElementById("select1");
            const selectDouble = document.getElementById("select2");
            const selectFamily = document.getElementById("select3");
        
            selectSingle.innerHTML = `<option disabled selected value="">Pilih Nomor Tenda</option>`;
            selectDouble.innerHTML = `<option disabled selected value="">Pilih Nomor Tenda</option>`;
            selectFamily.innerHTML = `<option disabled selected value="">Pilih Nomor Tenda</option>`;
        
            data.forEach(tent => {
                const option = document.createElement("option");
                option.value = tent.nomor_tent;
                option.textContent = tent.nomor_tent;
            
                if (tent.status === "tidak tersedia") {
                    option.disabled = true;
                    option.textContent += " (Tidak tersedia)";
                }
            
                // kategori per paket
                if (tent.paket_id == 1) {
                    selectSingle.appendChild(option);
                } else if (tent.paket_id == 2) {
                    selectDouble.appendChild(option);
                } else if (tent.paket_id == 3) {
                    selectFamily.appendChild(option);
                }
            });
        }

        /*add to cart*/
        function addItem(selectId, listId, category) {
            const select = document.getElementById(selectId);
            const list = document.getElementById(listId);
            const value = select.value;


            if (!value) {
                alert("Pilih tenda terlebih dahulu!");
                return;
            }
        
            let stored;
            try {
                stored = JSON.parse(localStorage.getItem("tendaDipilih")) || {};
            } catch {
                stored = {};
            }
        
            if (!stored.single) stored.single = [];
            if (!stored.double) stored.double = [];
            if (!stored.family) stored.family = [];
        
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
            localStorage.setItem("tendaDipilih", JSON.stringify(stored));
        }


        /* reset */
        function resetSelection() {
            if (confirm("Apakah Anda yakin ingin menghapus semua pilihan?")) {
                localStorage.setItem(STORAGE_KEY, JSON.stringify({ single: [], double: [], family: [] }));
                location.reload();
            }
        }

        document.addEventListener("DOMContentLoaded", loadTentsFromAPI);
    </script>


@endsection
