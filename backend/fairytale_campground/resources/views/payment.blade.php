@extends('index')

@section('title')
    Payment - FairyTale Campground
@endsection

@section('custom_css')
    <meta name="api-token" content="{{ auth()->user()->api_token ?? '' }}">

    <style>
        body {
            background: #ffffff;
            padding-top: 72px;
            color: #1d4807;
        }

        .method-card {
            cursor: pointer;
            transition: transform .12s;
        }

        .method-card:hover {
            transform: translateY(-4px);
        }

        .disabled-link {
            pointer-events: none;
            opacity: .6;
        }

        .btn-file {
            overflow: hidden;
            position: relative;
        }

        .btn-file input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <main class="col-lg-8">
            <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
                <div class="container mb-4" style="max-width: 800px;">
                <!-- Ringkasan Pesanan -->
                <div class="card card-pay mb-4 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">Ringkasan Pesanan</h4>
                        <div id="orderSummaryBlock" class="mt-3">
                            <!-- List Summary payment -->
                            <div class="text-center text-muted">Memuat ringkasan...</div>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="container" style="max-width: 800px;"></div>
                <!-- Pilih Metode Pembayaran -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Pilih Metode Pembayaran</h5>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div id="bankCard" class="card method-card h-100" data-method="bank">
                                    <div class="card-body">
                                        <h6 class="card-title">Transfer Bank</h6>
                                        <p class="card-text small text-muted">Transfer antar bank. Proses verifikasi
                                            manual/otomatis setelah upload bukti.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="ewalletCard" class="card method-card h-100" data-method="ewallet">
                                    <div class="card-body">
                                        <h6 class="card-title">E-wallet</h6>
                                        <p class="card-text small text-muted">Contoh: OVO / GoPay / Dana — ikuti
                                            instruksi pada layar.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        

                        <!-- Instruksi Pembayaran -->
                        <div id="paymentInstructions" class="mt-3 d-none">
                            <div class="alert alert-secondary">
                                <strong id="chosenMethodLabel"></strong>
                                <div id="chosenMethodInstructions" class="mt-2 small"></div>
                            </div>
                        </div>

                        <!-- Upload Bukti -->
                        <div id="uploadSection" class="mt-3 d-none">
                            <label class="form-label">Upload Bukti Transfer / Pembayaran</label>
                            <div class="mb-3">
                                <div class="btn btn-outline-success btn-file">
                                    Pilih file gambar...
                                    <input id="fileInput" accept="image/*" type="file">
                                </div>
                            </div>

                            <div id="previewWrap" class="mb-3 d-none">
                                <p class="small mb-1">Preview:</p>
                                <img id="previewImg"
                                    style="max-width: 320px; max-height:240px; border-radius:8px; border:1px solid #ddd;"
                                    alt="preview">
                            </div>

                            <div class="d-flex gap-2">
                                <button id="submitProofBtn" class="btn btn-success">Submit Bukti Pembayaran</button>
                                <button id="cancelOrderBtn" class="btn btn-outline-danger">Batalkan Pesanan</button>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>

    <script type="module">
        import { generateOrderSummary } from "/order_summary_flow.js";

        
        const summary = generateOrderSummary();
        
        const orderSummaryBlock = document.getElementById("orderSummaryBlock");
        
        const currentBooking = {
            id: 'local-' + Math.random().toString(36).slice(2, 9),
            items: summary.items || [],
            checkIn: summary.checkIn,
            checkOut: summary.checkOut,
            nights: summary.nights,
            total: summary.total,
            status: "Pending",
            paymentMethod: null,
            paymentProof: null,
            paidAt: null
        };
        
        if (!currentBooking.items || currentBooking.items.length === 0) {
            orderSummaryBlock.innerHTML = `
            <div class="alert alert-warning mb-0">Belum ada tenda yang dipilih. Silakan kembali ke <a href="./pickdate.html">Booking</a>.</div>
            `;
        } else {
            orderSummaryBlock.innerHTML = `
            <div class="row">
            <div class="col-8">
            <ul class="list-unstyled mb-0">
            ${currentBooking.items.map((it, idx) => `<li><strong>${it.tentType}</strong> — Tenda #${it.tentNumber || (idx + 1)}</li>`).join("")}
            </ul>
            <div class="mt-2 small text-muted">Check-in: ${currentBooking.checkIn} — Check-out: ${currentBooking.checkOut} (${currentBooking.nights} malam)</div>
            </div>
            <div class="col-4 text-end">
            <div class="h5 text-black fw-bold">Rp ${Number(currentBooking.total || 0).toLocaleString('id-ID')}</div>
            <div class="small text-muted">Order ID: <code>${currentBooking.id}</code></div>
            <div class="mt-2"><span id="statusBadge" class="badge bg-warning text-dark">Pending</span></div>
            </div>
            </div>
            `;
        }
        
        // pilih metode bayar
        const bankCard = document.getElementById("bankCard");
        const ewalletCard = document.getElementById("ewalletCard");
        const paymentInstructions = document.getElementById("paymentInstructions");
        const chosenMethodLabel = document.getElementById("chosenMethodLabel");
        const chosenMethodInstructions = document.getElementById("chosenMethodInstructions");
        const uploadSection = document.getElementById("uploadSection");
        const previewWrap = document.getElementById("previewWrap");
        const previewImg = document.getElementById("previewImg");
        const fileInput = document.getElementById("fileInput");
        const submitProofBtn = document.getElementById("submitProofBtn");
        const cancelOrderBtn = document.getElementById("cancelOrderBtn");
        
        function showInstructions(method) {
            paymentInstructions.classList.remove("d-none");
            uploadSection.classList.remove("d-none");
            chosenMethodLabel.textContent = method === "bank" ? "Transfer Bank" : "E-wallet";
            currentBooking.paymentMethod = method;
            
            if (method === "bank") {
                chosenMethodInstructions.innerHTML = `
            <div>
            Transfer ke <strong>Bank Demo (123-456-789)</strong> a.n. <em>Fairytale Campground</em>.<br>
            Jumlah: <strong>Rp ${Number(currentBooking.total || 0).toLocaleString('id-ID')}</strong><br>
            Setelah transfer, upload bukti di bawah (foto/ss) untuk verifikasi.
            </div>
            `;
        } else {
            chosenMethodInstructions.innerHTML = `
            <div>
            Bayar via E-wallet: <strong>Scan QR / Kirim ke 0812-XXXX-XXXX (Fairytale)</strong>.<br>
            Jumlah: <strong>Rp ${Number(currentBooking.total || 0).toLocaleString('id-ID')}</strong><br>
            Upload bukti pembayaran setelah selesai.
            </div>
            `;
        }
    }
    
    [bankCard, ewalletCard].forEach(card => {
        card.addEventListener('click', () => {
            const method = card.getAttribute('data-method');
            // highlight selected
            [bankCard, ewalletCard].forEach(c => c.classList.remove('border-success'));
            card.classList.add('border-success');
            showInstructions(method);
        });
    });
    
    // file preview
    fileInput.addEventListener('change', (e) => {
        const f = e.target.files[0];
        if (!f) return;
        const reader = new FileReader();
        reader.onload = function (evt) {
            previewImg.src = evt.target.result;
            previewWrap.classList.remove('d-none');
        };
        reader.readAsDataURL(f);
    });
    
    const token = document.querySelector("meta[name='api-token']").content;
    const pemesananId = new URLSearchParams(window.location.search).get("pemesanan_id");
    // submit bukti, update status menjadi Paid
    submitProofBtn.addEventListener('click', async () => {
        if (!pemesananId) {
                alert("ID pemesanan tidak ditemukan.");
                return;
            }
            if (!fileInput.files[0]) {
                alert("Silakan pilih bukti pembayaran.");
                return;
            }
        
            const formData = new FormData();
            formData.append("pemesanan_id", pemesananId);
            formData.append("total_pembayaran", currentBooking.total);
            formData.append("metode_pembayaran", currentBooking.paymentMethod === "bank" ? "transfer_bank" : "qris");
            formData.append("bukti", fileInput.files[0]);
        
            try {
                const res = await fetch("/api/pembayaran", {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Authorization": "Bearer {{ auth()->user()->api_token ?? '' }}"
                    },
                    body: formData
                });
            
                const json = await res.json();
            
                if (!res.ok) {
                    console.error(json);
                    alert("Gagal submit pembayaran: " + json.message);
                    return;
                }
            
                // sukses
                const sb = document.getElementById("statusBadge");
                if (sb) { sb.className = "badge bg-success"; sb.textContent = "Menunggu Verifikasi Admin"; }
            
                alert("Pembayaran dikirim! Menunggu verifikasi admin.");
            
                // opsional redirect
                window.location.href = "/payment/status?pemesanan_id=" + pemesananId;
            
            } catch (err) {
                console.error(err);
                alert("Terjadi kesalahan saat mengirim pembayaran.");
            }
        });


        // cancel order
        cancelOrderBtn.addEventListener('click', () => {
            if (!confirm("Batalkan pesanan ini?")) return;
            if (!currentBooking) return;
            currentBooking.status = "Cancelled";
            const sb = document.getElementById("statusBadge");
            if (sb) { sb.className = "badge bg-danger"; sb.textContent = "Cancelled"; }

            submitProofBtn.disabled = true;
            fileInput.disabled = true;
            bankCard.classList.add('disabled');
            ewalletCard.classList.add('disabled');
        });

        // Note: tidak ada penyimpanan riwayat, semua bersifat dalam memori.
    </script>
@endsection