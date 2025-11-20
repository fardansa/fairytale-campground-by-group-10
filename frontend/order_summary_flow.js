//AI bgt buat js, biar ada dulu sih.
// order_summary_flow.js

// --- Utility ---
function getLocal(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : null;
}

function setLocal(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
}

// --- FROM detail_tanggal.html ---
export function saveDates(checkIn, checkOut) {
    setLocal("checkIn", checkIn);
    setLocal("checkOut", checkOut);
}

export function getDates() {
    return {
        checkIn: getLocal("checkIn"),
        checkOut: getLocal("checkOut"),
    };
}

// --- FROM PILIHAN TENDA.html ---
export function saveSelectedTents(tentArray) {
    setLocal("selectedTents", tentArray);
}

export function getSelectedTents() {
    return getLocal("selectedTents") || [];
}

// --- PRICE TABLE ---
const priceTable = {
    "Single Tent": 150000,
    "Double Tent": 250000,
    "Family Tent": 400000,
};

export function computeNightCount() {
    const { checkIn, checkOut } = getDates();

    // Fallback: Jika tanggal belum dipilih, anggap 0 atau beri nilai default untuk testing
    if (!checkIn || !checkOut) return 0;

    const d1 = new Date(checkIn);
    const d2 = new Date(checkOut);
    const diff = (d2 - d1) / (1000 * 60 * 60 * 24);
    return diff > 0 ? diff : 0;
}

// --- SUMMARY CALCULATION ---
export function generateOrderSummary() {
    const nights = computeNightCount();
    const tents = getSelectedTents();

    // Mengambil tanggal untuk ditampilkan (string)
    const dates = getDates();

    let list = [];
    let grandTotal = 0;

    tents.forEach((t, index) => {
        const unitPrice = priceTable[t] || 0; // Safety check jika tipe tenda typo
        const subtotal = unitPrice * (nights === 0 ? 1 : nights); // Jika malam 0 (belum pilih tgl), hitung harga 1 malam agar user lihat harga dasar
        grandTotal += subtotal;

        list.push({
            id: index + 1,
            tentType: t,
            nights: nights === 0 ? 1 : nights, // Tampilkan 1 jika belum ada tanggal
            unitPrice,
            subtotal,
            tentNumber: index + 1,
            lockerNumber: index + 1,
        });
    });

    return {
        checkIn: dates.checkIn || "Belum dipilih",
        checkOut: dates.checkOut || "Belum dipilih",
        nights: nights,
        items: list,
        total: grandTotal,
    };
}

// --- CLEAR ALL WHEN PAYMENT FINISHED ---
export function clearAllData() {
    localStorage.removeItem("checkIn");
    localStorage.removeItem("checkOut");
    localStorage.removeItem("selectedTents");
    localStorage.removeItem("tendaDipilih"); // Bersihkan juga raw datanya
}