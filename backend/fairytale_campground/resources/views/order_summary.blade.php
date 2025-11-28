@extends('index')

@section('title')

@section('content')

<a href="javascript:history.back()" class="btn-back">← Kembali</a>

<div class="container py-4">
    <h2 class="mb-4 text-center fw-semibold">Order Summary Anda</h2>

    <div class="row g-4">
        <div class="col-lg-7">
            <div id="ticketContainer"></div>
        </div>

        <div class="col-lg-5">
            <div id="priceCard" class="card p-4 shadow-sm" style="background-color:#fff;"></div>
        </div>
    </div>
</div>

<script type="module">
    import { generateOrderSummary } from "/order_summary_flow.js";

    const summary = generateOrderSummary();
    
    const tentImages = {
        "Single Tent": "/img/tenda1.png",
        "Double Tent": "/img/tenda2.png",
        "Family Tent": "/img/tenda3.png",
        "default": "/img/default.png"
    };
    
    const container = document.getElementById("ticketContainer");
    const priceContainer = document.getElementById("priceCard");
    
    if (summary.items.length === 0) {
        container.innerHTML = `<div class="alert alert-warning">Belum ada tenda yang dipilih.</div>`;
    } else {
        const cardsHTML = summary.items.map(item => `
        <div class="card mb-3 p-3 shadow-sm">
        <div class="d-flex align-items-start gap-3">
        
        <img src="${tentImages[item.tentType] || tentImages['default']}" 
        alt="${item.tentType}"
        style="width:120px;height:90px;object-fit:cover;border-radius:4px;">

                    <div class="flex-grow-1">
                    <h5 class="mb-1 fw-bold">${item.tentType}</h5>
                    <span class="badge bg-secondary mb-2">Tenda #${item.tentNumber}</span>
                    
                    <hr class="my-2">
                    
                    <div class="row text-small" style="font-size:0.9rem;">
                    <div class="col-6">
                    <small class="text-muted">Check-in:</small><br>
                    <strong>${summary.checkIn}</strong>
                    </div>
                    <div class="col-6 text-end">
                    <small class="text-muted">Check-out:</small><br>
                    <strong>${summary.checkOut}</strong>
                    </div>
                    </div>
                    <div class="mt-2 text-end text-success fw-bold">
                    ${item.nights} Malam
                    </div>
                    </div>
                    
                    </div>
                    </div>
                    `).join("");
                    
                    container.innerHTML = cardsHTML;
                }
                
                priceContainer.innerHTML = `
                <h5 class="fw-semibold mb-3">Ringkasan Harga</h5>
                
                ${summary.items.map(i => `
                <div class="d-flex justify-content-between mb-2">
                <span>${i.tentType} (x${i.nights} malam)</span>
                <span>Rp ${i.subtotal.toLocaleString('id-ID')}</span>
                </div>
                `).join("")}
                
                <hr>
                
                <div class="d-flex justify-content-between mb-3">
                <span class="fw-bold">Total Pembayaran</span>
                <span class="fw-bold text-success fs-5">Rp ${summary.total.toLocaleString('id-ID')}</span>
                </div>
                
                <a href="/payment?pemesanan_id=${generatedId}
" class="btn btn-success w-100 py-2 fw-bold">
                Bayar
                </a>
                `;
                localStorage.setItem("pemesanan_id", pemesananId);
            </script>

@endsection
