@extends('index')

@section('title')

@section('content')
<a href="javascript:history.back()" class="btn-back mt-4 ms-4">← Kembali</a>

<div class="container py-5">
    <h2 class="text-center mb-4">Hasil Pilihan Tenda Anda</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Single Tent</h5>
            <ul id="hasilSingle" class="mb-4"></ul>

            <h5>Double Tent</h5>
            <ul id="hasilDouble" class="mb-4"></ul>

            <h5>Family Tent</h5>
            <ul id="hasilFamily"></ul>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ url('/order_summary') }}" class="btn btn-secondary">Lanjut ke Order Summary</a>
    </div>
</div>

<script type="module">
    import { saveSelectedTents, getDates } from "/order_summary_flow.js";

    let raw = JSON.parse(localStorage.getItem("tendaDipilih") || '{"single":[],"double":[],"family":[]}');

    raw.single = Array.isArray(raw.single) ? raw.single : [];
    raw.double = Array.isArray(raw.double) ? raw.double : [];
    raw.family = Array.isArray(raw.family) ? raw.family : [];


    function renderList(id, items) {
        const ul = document.getElementById(id);
        ul.innerHTML = "";

        if (!items || items.length === 0) {
            ul.innerHTML = "<li class='text-muted'>-</li>";
            return;
        }

        items.forEach(item => {
            const li = document.createElement("li");
            li.textContent = item;
            ul.appendChild(li);
        });
    }

    renderList("hasilSingle", raw.single);
    renderList("hasilDouble", raw.double);
    renderList("hasilFamily", raw.family);


    // Konversi data
    const combined = [
        ...raw.single,
        ...raw.double,
        ...raw.family
    ];

    saveSelectedTents(combined);
    console.log("Data tenda disimpan:", combined);

    const dates = getDates();
    const el = document.createElement("div");
    el.className = "mb-3 text-center";
    el.innerHTML = `<strong>Check-in:</strong> ${dates.checkIn || 'Belum dipilih'} &nbsp; | &nbsp; <strong>Check-out:</strong> ${dates.checkOut || 'Belum dipilih'}`;
    const container = document.querySelector(".card .card-body");
    if (container) container.prepend(el);
</script>
@endsection
