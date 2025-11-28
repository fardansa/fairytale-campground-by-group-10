@extends('index')

@section('title')

@section('custom_css')
    <style>
        body {
            padding-top: 64px;
        }

        header.navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, .08);
            z-index: 1030;
            display: flex;
            align-items: center;
        }

        .navbar-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
        }

        .navbar-logo {
            font-weight: 700;
            color: #1d4807;
            text-decoration: none;
        }

        .navbar-menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #333;
            padding: 8px 10px;
            border-radius: 6px;
        }

        .navbar-menu a:hover {
            background: rgba(0, 0, 0, 0.03);
        }

        .navbar-auth a {
            margin-left: 8px;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
        }

        .btn-outline {
            border: 1px solid #1d4807;
            color: #1d4807;
        }

        .btn-solid {
            background: #1d4807;
            color: #fff;
            border: 1px solid #1d4807;
        }

        .sidebar {
            height: calc(100vh - 64px);
            top: 64px;
            position: sticky;
            overflow: auto;
        }

        main {
            min-height: calc(100vh - 64px);
            padding-top: 1rem;
        }

        .small-table td,
        .small-table th {
            font-size: .9rem;
        }

        .sidebar .nav-link {
            color: #b4b4b4 !important;
        }

        .sidebar .nav-link:hover {
            color: #000 !important;
        }

        .sidebar .nav-link.active {
            color: #1d4807 !important;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-body-tertiary sidebar border-end">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a id="navDashboard"
                                class="nav-link active d-flex align-items-center gap-2" href="#"
                                data-view="dashboard"><i class="bi bi-house"></i> Dashboard</a></li>
                        <li class="nav-item"><a id="navTents" class="nav-link d-flex align-items-center gap-2" href="#"
                                data-view="tents"><i class="bi bi-archive"></i> Tents (Manage)</a></li>
                        <li class="nav-item"><a id="navBookings" class="nav-link d-flex align-items-center gap-2"
                                href="#" data-view="bookings"><i class="bi bi-journal-check"></i> Bookings (Orders)</a>
                        </li>
                        <li class="nav-item"><a class="nav-link d-flex align-items-center gap-2" href="#"
                                data-view="customers"><i class="bi bi-people"></i> Customers</a></li>
                        <li class="nav-item"><a class="nav-link d-flex align-items-center gap-2" href="#"
                                data-view="reports"><i class="bi bi-graph-up"></i> Reports</a></li>
                    </ul>

                    <hr class="my-3">

                    <ul class="nav flex-column mb-2">
                        <li class="nav-item"><a class="nav-link d-flex align-items-center gap-2" href="#"><i
                                    class="bi bi-gear"></i> Settings</a></li>
                        <li class="nav-item"><a id="btnSignOut" class="nav-link d-flex align-items-center gap-2"
                                href="#"><i class="bi bi-door-closed"></i> Sign Out</a></li>
                    </ul>
                </div>
            </nav>

            <!-- MAIN -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <!-- DASHBOARD VIEW -->
                <section id="view-dashboard">
                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                            </div>
                            <button type="button"
                                class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1"><i
                                    class="bi bi-calendar3"></i> This week</button>
                        </div>
                    </div>

                    <!-- Chart -->
                    <canvas class="my-4 w-100" id="myChart" height="200"></canvas>

                    <!-- Recent Tents (container for JS) -->
                    <h2>Recent Tents</h2>
                    <div id="recentTentsContainer" class="row mb-4"></div>

                    <!-- Recent Bookings -->
                    <h2>Recent Bookings</h2>
                    <div id="recentBookingsContainer" class="table-responsive small-table"></div>
                </section>

                <!-- TENTS MANAGEMENT VIEW -->
                <section id="view-tents" class="d-none">
                    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h4">Manage Tents</h1>
                        <div><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTentModal"><i
                                    class="bi bi-plus-lg"></i> Add Tent Type</button></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" id="tentsTable">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Harga / malam</th>
                                    <th>Fasilitas</th>
                                    <th>Total Unit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </section>

                <!-- BOOKINGS VIEW -->
                <section id="view-bookings" class="d-none">
                    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h4">Bookings / Orders</h1>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th>Nama User</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Jenis Tenda</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </section>

                <section id="view-customers" class="d-none">
                    <h1 class="h4 pt-3 pb-2 mb-3 border-bottom">Customers</h1>
                    <p>Placeholder.</p>
                </section>
                <section id="view-reports" class="d-none">
                    <h1 class="h4 pt-3 pb-2 mb-3 border-bottom">Reports</h1>
                    <p>Placeholder.</p>
                </section>

            </main>
        </div>
    </div>

    <!-- MODALS (login / add / edit / delete / booking detail) -->
    <!-- Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="loginForm" class="modal-content needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Admin Login</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label for="loginEmail" class="form-label">Email atau Username</label><input
                            id="loginEmail" class="form-control" required minlength="3">
                        <div class="invalid-feedback">Masukkan email/username (min 3 karakter).</div>
                    </div>
                    <div class="mb-3"><label for="loginPassword" class="form-label">Password</label><input
                            id="loginPassword" type="password" class="form-control" required minlength="4">
                        <div class="invalid-feedback">Password minimal 4 karakter.</div>
                    </div>
                    <div id="loginError" class="text-danger d-none small">Login gagal — simulasi.</div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Login</button></div>
            </form>
        </div>
    </div>

    <!-- Add Tent -->
    <div class="modal fade" id="addTentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="addTentForm" class="modal-content needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Add Tent Type</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama tenda</label><input id="tentName"
                            class="form-control" required>
                        <div class="invalid-feedback">Nama tenda wajib diisi.</div>
                    </div>
                    <div class="mb-3"><label class="form-label">Harga per malam (Rp)</label><input id="tentPrice"
                            type="number" class="form-control" required min="0">
                        <div class="invalid-feedback">Masukkan harga yang valid.</div>
                    </div>
                    <div class="mb-3"><label class="form-label">Fasilitas</label><textarea id="tentFacilities"
                            class="form-control" rows="2" placeholder="Pisahkan dengan koma"></textarea></div>
                    <div class="mb-3"><label class="form-label">Jumlah unit</label><input id="tentUnits" type="number"
                            class="form-control" required min="1" value="1">
                        <div class="invalid-feedback">Masukkan jumlah unit (minimal 1).</div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button><button type="submit"
                        class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>

    <!-- Edit Tent -->
    <div class="modal fade" id="editTentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="editTentForm" class="modal-content needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tent Type</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <input type="hidden" id="editTentId">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama tenda</label><input id="editTentName"
                            class="form-control" required>
                        <div class="invalid-feedback">Nama tenda wajib diisi.</div>
                    </div>
                    <div class="mb-3"><label class="form-label">Harga per malam (Rp)</label><input id="editTentPrice"
                            type="number" class="form-control" required min="0">
                        <div class="invalid-feedback">Masukkan harga yang valid.</div>
                    </div>
                    <div class="mb-3"><label class="form-label">Fasilitas</label><textarea id="editTentFacilities"
                            class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Jumlah unit</label><input id="editTentUnits"
                            type="number" class="form-control" required min="1">
                        <div class="invalid-feedback">Masukkan jumlah unit (minimal 1).</div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button><button type="submit"
                        class="btn btn-primary">Perbarui</button></div>
            </form>
        </div>
    </div>

    <!-- Delete Confirm -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="mb-0">Apakah kamu yakin ingin menghapus item ini?</p>
                </div>
                <div class="modal-footer"><button id="btnCancelDelete" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button><button id="btnConfirmDelete"
                        class="btn btn-danger">Hapus</button></div>
            </div>
        </div>
    </div>

    <!-- Booking Detail / Verify -->
    <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pembayaran</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-4">Nama</dt>
                        <dd id="bdName" class="col-8"></dd>
                        <dt class="col-4">Check-in</dt>
                        <dd id="bdCheckin" class="col-8"></dd>
                        <dt class="col-4">Check-out</dt>
                        <dd id="bdCheckout" class="col-8"></dd>
                        <dt class="col-4">Jenis Tenda</dt>
                        <dd id="bdTent" class="col-8"></dd>
                        <dt class="col-4">Total</dt>
                        <dd id="bdTotal" class="col-8"></dd>
                        <dt class="col-4">Status</dt>
                        <dd id="bdStatus" class="col-8"></dd>
                    </dl>
                    <div id="bdPaymentNote" class="mb-2 small text-muted">Bukti pembayaran: <em>(simulasi)</em></div>
                    <div id="bdPaymentImg"></div>
                </div>
                <div class="modal-footer"><button id="btnVerifyPayment" class="btn btn-success">Verifikasi
                        Pembayaran</button><button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ---------- initial data (updated to Single/Double/Family) ----------
        let tents = [
            {
                id: 1,
                name: 'Single Tent',
                price: 150000,
                facilities: '1 sleeping pad atau matras single,1 sleeping bag,Lampu tenda LED,Meja kecil lipat,Akses area api unggun umum,Free refill air minum,Charging station di area bersama,Toilet & kamar mandi umum',
                units: 5
            },
            {
                id: 2,
                name: 'Double Tent',
                price: 250000,
                facilities: '2 sleeping pad atau 1 matras double,2 sleeping bag,Lampu tenda LED,Meja & kursi camping,Area api unggun bersama,Free refill air minum,Charging station di area bersama,Toilet & kamar mandi umum',
                units: 3
            },
            {
                id: 3,
                name: 'Family Tent',
                price: 400000,
                facilities: 'Matras sesuai kapasitas (queen + single / 4 sleeping pad),4 sleeping bag,Lampu tenda LED tambahan,Meja piknik + kursi lipat,Area api unggun khusus family,Free refill air minum,Charging station di area bersama,Toilet & kamar mandi umum',
                units: 2
            }
        ];

        let bookings = [
            { id: 1, name: 'Budi', checkin: '2025-12-01', checkout: '2025-12-03', tent: 'Single Tent', total: 300000, status: 'Pending', paymentImg: null },
            { id: 2, name: 'Siti', checkin: '2025-11-28', checkout: '2025-11-29', tent: 'Double Tent', total: 250000, status: 'Paid', paymentImg: null }
        ];

        // helper
        function formatIDR(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value); }
        function escapeHtml(s) { if (s === null || s === undefined) return ''; return String(s).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m]); }

        // ---------- render functions ----------
        function renderTentsTable() {
            const tbody = document.querySelector('#tentsTable tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            tents.forEach(t => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
          <td>${escapeHtml(t.name)}</td>
          <td>${formatIDR(t.price)}</td>
          <td>${escapeHtml(t.facilities)}</td>
          <td>${t.units}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary me-1" data-action="edit" data-id="${t.id}"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-outline-danger" data-action="delete" data-id="${t.id}"><i class="bi bi-trash"></i></button>
          </td>
        `;
                tbody.appendChild(tr);
            });
            renderRecentTents(); // keep recent tents in sync with data
        }

        function renderBookingsTable() {
            const tbody = document.querySelector('#bookingsTable tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            bookings.forEach(b => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
          <td>${escapeHtml(b.name)}</td>
          <td>${escapeHtml(b.checkin)}</td>
          <td>${escapeHtml(b.checkout)}</td>
          <td>${escapeHtml(b.tent)}</td>
          <td>${formatIDR(b.total)}</td>
          <td><span class="badge ${b.status === 'Paid' ? 'bg-success' : (b.status === 'Cancelled' ? 'bg-danger' : 'bg-warning text-dark')}">${b.status}</span></td>
          <td>
            <button class="btn btn-sm btn-outline-secondary me-1" data-action="detail" data-id="${b.id}">Detail</button>
            ${b.status !== 'Paid' ? `<button class="btn btn-sm btn-success" data-action="verify" data-id="${b.id}">Verifikasi Pembayaran</button>` : ''}
          </td>
        `;
                tbody.appendChild(tr);
            });
            bindBookingsActions();
            renderRecentBookings();
        }

        // dynamic recent tents (uses current tents data)
        function renderRecentTents() {
            const container = document.getElementById('recentTentsContainer');
            if (!container) return;
            const items = tents.slice(0, 3);
            container.innerHTML = items.map(t => `
        <div class="col-md-4 mb-3">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title fw-bold">${escapeHtml(t.name)}</h5>
              <h6 class="text-muted">${formatIDR(t.price)} / malam</h6>
              <ul class="small mt-2 mb-0">${escapeHtml(t.facilities).split(',').map(f => `<li>${f.trim()}</li>`).join('')}</ul>
            </div>
          </div>
        </div>
      `).join('');
        }

        function renderRecentBookings() {
            const container = document.getElementById('recentBookingsContainer');
            if (!container) return;
            const recent = bookings.slice(0, 5);
            if (recent.length === 0) { container.innerHTML = '<p class="small text-muted">No recent bookings.</p>'; return; }
            let html = `<table class="table table-sm table-striped"><thead><tr><th>Nama</th><th>Tenda</th><th>Check-in</th><th>Status</th></tr></thead><tbody>`;
            recent.forEach(b => { html += `<tr><td>${escapeHtml(b.name)}</td><td>${escapeHtml(b.tent)}</td><td>${escapeHtml(b.checkin)}</td><td>${escapeHtml(b.status)}</td></tr>`; });
            html += `</tbody></table>`;
            container.innerHTML = html;
        }

        // ---------- Add Tent ----------
        const addTentFormEl = document.getElementById('addTentForm');
        if (addTentFormEl) {
            addTentFormEl.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!addTentFormEl.checkValidity()) { addTentFormEl.classList.add('was-validated'); return; }
                const name = document.getElementById('tentName').value.trim();
                const price = Number(document.getElementById('tentPrice').value) || 0;
                const facilities = document.getElementById('tentFacilities').value.trim();
                const units = Number(document.getElementById('tentUnits').value) || 1;
                const newId = tents.length ? Math.max(...tents.map(x => x.id)) + 1 : 1;
                const newTent = { id: newId, name, price, facilities, units };
                console.log('Simulate POST /tents', newTent);
                tents.push(newTent);
                renderTentsTable();
                addTentFormEl.reset();
                addTentFormEl.classList.remove('was-validated');
                const addModal = bootstrap.Modal.getInstance(document.getElementById('addTentModal'));
                if (addModal) addModal.hide();
            });
        }

        // ---------- Edit / Delete Tents (table buttons) ----------
        const tentsTableEl = document.getElementById('tentsTable');
        if (tentsTableEl) {
            tentsTableEl.addEventListener('click', function (e) {
                const btn = e.target.closest('button');
                if (!btn) return;
                const action = btn.dataset.action;
                const id = Number(btn.dataset.id);
                if (action === 'edit') {
                    const tent = tents.find(t => t.id === id); if (!tent) return;
                    document.getElementById('editTentId').value = tent.id;
                    document.getElementById('editTentName').value = tent.name;
                    document.getElementById('editTentPrice').value = tent.price;
                    document.getElementById('editTentFacilities').value = tent.facilities;
                    document.getElementById('editTentUnits').value = tent.units;
                    new bootstrap.Modal(document.getElementById('editTentModal')).show();
                } else if (action === 'delete') {
                    window._pendingDelete = { type: 'tent', id };
                    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
                }
            });
        }

        // edit submit
        const editTentFormEl = document.getElementById('editTentForm');
        if (editTentFormEl) {
            editTentFormEl.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!editTentFormEl.checkValidity()) { editTentFormEl.classList.add('was-validated'); return; }
                const id = Number(document.getElementById('editTentId').value);
                const tent = tents.find(t => t.id === id); if (!tent) return;
                tent.name = document.getElementById('editTentName').value.trim();
                tent.price = Number(document.getElementById('editTentPrice').value) || 0;
                tent.facilities = document.getElementById('editTentFacilities').value.trim();
                tent.units = Number(document.getElementById('editTentUnits').value) || 1;
                console.log('Simulate PUT /tents/' + id, tent);
                renderTentsTable();
                editTentFormEl.classList.remove('was-validated');
                const em = bootstrap.Modal.getInstance(document.getElementById('editTentModal'));
                if (em) em.hide();
            });
        }

        // delete confirm
        const btnConfirmDelete = document.getElementById('btnConfirmDelete');
        if (btnConfirmDelete) {
            btnConfirmDelete.addEventListener('click', function () {
                const pending = window._pendingDelete; if (!pending) return;
                if (pending.type === 'tent') { tents = tents.filter(t => t.id !== pending.id); console.log('Simulate DELETE /tents/' + pending.id); renderTentsTable(); }
                window._pendingDelete = null;
                const dm = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')); if (dm) dm.hide();
            });
        }

        // ---------- Bookings actions ----------
        function bindBookingsActions() {
            document.querySelectorAll('#bookingsTable button[data-action]').forEach(btn => {
                btn.onclick = function () {
                    const action = btn.dataset.action;
                    const id = Number(btn.dataset.id);
                    const booking = bookings.find(b => b.id === id);
                    if (!booking) return;
                    if (action === 'detail') openBookingDetail(booking);
                    if (action === 'verify') openBookingDetail(booking, true);
                };
            });
        }

        function openBookingDetail(booking, autoVerify = false) {
            document.getElementById('bdName').textContent = booking.name;
            document.getElementById('bdCheckin').textContent = booking.checkin;
            document.getElementById('bdCheckout').textContent = booking.checkout;
            document.getElementById('bdTent').textContent = booking.tent;
            document.getElementById('bdTotal').textContent = formatIDR(booking.total);
            document.getElementById('bdStatus').textContent = booking.status;
            document.getElementById('bdPaymentImg').innerHTML = booking.paymentImg ? `<img src="${booking.paymentImg}" class="img-fluid">` : '<div class="small text-muted">Tidak ada bukti (simulasi)</div>';
            const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal')); modal.show();
            document.getElementById('btnVerifyPayment').onclick = function () {
                booking.status = 'Paid';
                renderBookingsTable();
                modal.hide();
            };
            if (autoVerify) {
                // do nothing auto for now; show modal so admin confirms
            }
        }

        // ---------- Login (simulation) ----------
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!loginForm.checkValidity()) { loginForm.classList.add('was-validated'); return; }
                const username = document.getElementById('loginEmail').value.trim();
                const password = document.getElementById('loginPassword').value;
                if (password.length >= 4 && username.length >= 3) {
                    document.getElementById('btnLogin').classList.add('d-none');
                    document.getElementById('btnRegister').classList.add('d-none');
                    document.getElementById('adminBadge').classList.remove('d-none');
                    document.getElementById('adminName').textContent = username;
                    const lm = bootstrap.Modal.getInstance(document.getElementById('loginModal')); if (lm) lm.hide();
                } else {
                    document.getElementById('loginError').classList.remove('d-none');
                }
            });
        }

        // ---------- Navigation view switching ----------
        document.querySelectorAll('[data-view]').forEach(a => {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                const view = this.dataset.view;
                document.querySelectorAll('section[id^="view-"]').forEach(s => s.classList.add('d-none'));
                const el = document.getElementById('view-' + view);
                if (el) el.classList.remove('d-none');
                document.querySelectorAll('#sidebarMenu .nav-link').forEach(n => n.classList.remove('active'));
                this.classList.add('active');
                if (view === 'tents') renderTentsTable();
                if (view === 'bookings') renderBookingsTable();
                if (view === 'dashboard') { renderTentsTable(); renderBookingsTable(); }
            });
        });

        // ---------- Init ----------
        renderTentsTable();
        renderBookingsTable();

        // rebind sign out
        const btnSignOut = document.getElementById('btnSignOut');
        if (btnSignOut) btnSignOut.addEventListener('click', function (e) { e.preventDefault(); document.getElementById('btnLogin').classList.remove('d-none'); document.getElementById('btnRegister').classList.remove('d-none'); document.getElementById('adminBadge').classList.add('d-none'); document.getElementById('adminName').textContent = 'Admin'; });

        // ---------- Chart (simple) ----------
        const ctx = document.getElementById('myChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: { labels: bookings.map(b => b.name), datasets: [{ label: 'Total Booking (simulasi)', data: bookings.map(b => b.total), fill: false, tension: 0.3 }] },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }

        // minimal: prevent throwing on missing elements when used elsewhere
        (function () { 'use strict'; })();
    </script>
@endsection