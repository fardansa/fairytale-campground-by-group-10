// order_summary_flow.js
// Utility module untuk menyimpan / membaca tanggal & selected tents,
// serta menghitung ringkasan order.
// Backward-compatible: akan membaca baik key lama ("checkin_date") maupun key baru ("checkIn").

function getLocal(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : null;
}
function setLocal(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
}

// --- DATES ---
// saveDates menyimpan ke 2 variant (compatibility)
export function saveDates(checkIn, checkOut) {
    setLocal("checkIn", checkIn);
    setLocal("checkOut", checkOut);
    // legacy keys (some earlier pages used these)
    setLocal("checkin_date", checkIn);
    setLocal("checkout_date", checkOut);
}

// getDates membaca dari beberapa kemungkinan key (prioritas: new keys)
export function getDates() {
    const checkIn = (localStorage.getItem("checkIn") && JSON.parse(localStorage.getItem("checkIn"))) ||
        (localStorage.getItem("checkin_date") && JSON.parse(localStorage.getItem("checkin_date"))) ||
        null;
    const checkOut = (localStorage.getItem("checkOut") && JSON.parse(localStorage.getItem("checkOut"))) ||
        (localStorage.getItem("checkout_date") && JSON.parse(localStorage.getItem("checkout_date"))) ||
        null;
    // Note: if stored as plain strings (not JSON), handle that:
    return {
        checkIn: typeof checkIn === "string" ? checkIn : (checkIn ? String(checkIn) : null),
        checkOut: typeof checkOut === "string" ? checkOut : (checkOut ? String(checkOut) : null),
    };
}

// --- SELECTED TENTS ---
export function saveSelectedTents(tentArray) {
    setLocal("selectedTents", tentArray);
    // legacy key used earlier:
    setLocal("tendaDipilih", tentArray);
}
export function getSelectedTents() {
    return getLocal("selectedTents") || getLocal("tendaDipilih") || [];
}

// --- PRICE TABLE ---
const priceTable = {
    "Single Tent": 150000,
    "Double Tent": 250000,
    "Family Tent": 400000,
};

// --- NIGHTS CALC ---
export function computeNightCount() {
    const { checkIn, checkOut } = getDates();
    if (!checkIn || !checkOut) return 0;
    const d1 = new Date(checkIn);
    const d2 = new Date(checkOut);
    const diff = (d2 - d1) / (1000 * 60 * 60 * 24);
    return diff > 0 ? diff : 0;
}

// --- SUMMARY ---
export function generateOrderSummary() {
    const nights = computeNightCount();
    const tents = getSelectedTents();
    const dates = getDates();

    let list = [];
    let grandTotal = 0;

    tents.forEach((t, idx) => {
        const unitPrice = priceTable[t] || 0;
        const usedNights = nights === 0 ? 1 : nights;
        const subtotal = unitPrice * usedNights;
        grandTotal += subtotal;

        list.push({
            id: idx + 1,
            tentType: t,
            nights: usedNights,
            unitPrice,
            subtotal,
            // tentNumber is just index-based placeholder;
            // if you have specific tent numbers saved, you can store them instead.
            tentNumber: idx + 1,
        });
    });

    return {
        checkIn: dates.checkIn || "Belum dipilih",
        checkOut: dates.checkOut || "Belum dipilih",
        nights,
        items: list,
        total: grandTotal,
    };
}

export function clearAllData() {
    localStorage.removeItem("checkIn");
    localStorage.removeItem("checkOut");
    localStorage.removeItem("checkin_date");
    localStorage.removeItem("checkout_date");
    localStorage.removeItem("selectedTents");
    localStorage.removeItem("tendaDipilih");
}
