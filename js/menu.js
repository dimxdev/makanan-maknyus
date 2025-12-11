// Inisialisasi array untuk menyimpan item di keranjang
let cart = [];

// Simpan data keranjang ke localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Ambil data keranjang dari localStorage
function loadCart() {
    const storedCart = localStorage.getItem('cart');
    if (storedCart) {
        try {
            cart = JSON.parse(storedCart);
        } catch (e) {
            console.error('Error parsing cart data:', e);
        }
    }
}

function addToCart(id, name, price, image) {
    // Validasi parameter
    if (!id || !name || !price || !image) {
        console.error("Data item tidak lengkap:", { id, name, price, image });
        alert("Data produk tidak valid. Tidak dapat menambahkan ke keranjang.");
        return;
    }

    // Periksa apakah item sudah ada di keranjang
    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        // Jika sudah ada, tambahkan quantity
        existingItem.quantity += 1;
    } else {
        // Jika belum ada, tambahkan sebagai item baru
        cart.push({
            id: id,
            name: name,
            price: price,
            image: image,
            quantity: 1
        });
    }

    renderCart(); // Perbarui tampilan keranjang
    saveCart();   // Simpan data ke localStorage
}

// Fungsi menambah quantity item
function addQuantity(index) {
    cart[index].quantity += 1; // Tambah jumlah quantity
    renderCart(); // Perbarui tampilan keranjang
    saveCart(); // Simpan ke localStorage
}

// Fungsi menghapus item satu per satu dari keranjang
function removeFromCart(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity -= 1; // Kurangi jumlah
    } else {
        cart.splice(index, 1); // Jika quantity 1, hapus item dari keranjang
    }
    renderCart(); // Perbarui tampilan keranjang
    saveCart(); // Simpan ke localStorage
}

// Fungsi untuk memproses checkout
function checkoutCart() {
    if (cart.length === 0) {
        alert("Keranjang belanja kosong!");
        return;
    }

    // Periksa apakah pengguna telah login
    const isLoggedIn = document.body.getAttribute('data-logged-in') === 'true';
    if (!isLoggedIn) {
        alert("Anda harus login terlebih dahulu untuk melanjutkan ke pembayaran.");
        window.location.href = 'login.php';
        return;
    }

    // Simpan data keranjang dan arahkan ke halaman pemesanan
    saveCart();
    window.location.href = 'pemesanan.php';
}

// Fungsi menampilkan item di keranjang
function renderCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalContainer = document.querySelector('.cart-total');
    cartItemsContainer.innerHTML = ''; // Kosongkan isi sebelumnya
    let total = 0;

    cart.forEach((item, index) => {
        total += item.price * item.quantity;

        const listItem = document.createElement('li');
        listItem.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="cart-item-image">
            <span>${item.name} - Rp ${item.price.toLocaleString()} x ${item.quantity}</span>
            <button onclick="addQuantity(${index})" class="quantity-btn plus-btn">+</button>
            <button onclick="removeFromCart(${index})" class="quantity-btn minus-btn">-</button>
        `;
        listItem.className = 'cart-item';

        cartItemsContainer.appendChild(listItem);
    });

    cartTotalContainer.textContent = `Total: Rp ${total.toLocaleString()}`; // Update total harga
    updateCartBadge(); // Update badge
}

// Fungsi untuk memperbarui badge keranjang
function updateCartBadge() {
    const cartBadge = document.getElementById('cart-badge');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0); // Hitung total quantity
    cartBadge.textContent = totalItems; // Perbarui teks badge

    // Sembunyikan badge jika keranjang kosong
    cartBadge.style.display = totalItems === 0 ? 'none' : 'block';
}

// Event listener untuk tombol checkout dan keranjang
document.addEventListener('DOMContentLoaded', () => {
    loadCart(); // Muat data keranjang dari localStorage
    renderCart(); // Perbarui tampilan keranjang
    updateCartBadge(); // Perbarui badge keranjang

    // Tambahkan event listener ke tombol checkout dan close cart
    document.getElementById('checkout-btn').addEventListener('click', checkoutCart);
    document.getElementById('close-cart-btn').addEventListener('click', () => {
        document.getElementById('cart-tab').style.right = '-650px';
    });

    document.querySelector('li.nav-item a#shopping-cart-button').addEventListener('click', (event) => {
        event.preventDefault();
        const cartTab = document.getElementById('cart-tab');
        cartTab.style.right = cartTab.style.right === '0px' ? '-650px' : '0px';
    });
});
