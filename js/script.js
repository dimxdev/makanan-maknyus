let cart = []; // Array untuk menyimpan item di keranjang

// Toggle Cart Tab
document.getElementById('shopping-cart-button').addEventListener('click', () => {
    const cartTab = document.getElementById('cart-tab');
    if (cartTab.style.right === '0px') {
        cartTab.style.right = '-350px'; // Sembunyikan
    } else {
        cartTab.style.right = '0px'; // Tampilkan
    }
});

// Fungsi menambahkan item ke keranjang
function addToCart(name, price, image) {
    const existingItem = cart.find(item => item.name === name);
    if (existingItem) {
        existingItem.quantity += 1; // Tambahkan jumlah
    } else {
        cart.push({ name, price, image, quantity: 1 }); // Tambahkan produk baru
    }
    renderCart(); // Update tampilan keranjang
}

// Fungsi menghapus item satu per satu dari keranjang
function removeFromCart(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity -= 1; // Kurangi jumlah quantity
    } else {
        cart.splice(index, 1); // Jika quantity 1, hapus item dari keranjang
    }
    renderCart(); // Update tampilan keranjang
}


// Fungsi checkout
function checkoutCart() {
    if (cart.length === 0) {
        alert("Keranjang belanja kosong!");
        return;
    }

    // Hitung total belanja
    let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

    // Simpan data keranjang ke localStorage agar bisa diakses di pembayaran.html
    localStorage.setItem('cart', JSON.stringify(cart));
    localStorage.setItem('total', total);

    // Arahkan ke halaman pembayaran
    window.location.href = 'pemesanan.html';
}


// Fungsi menampilkan item di keranjang
function renderCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalContainer = document.getElementById('cart-total');
    cartItemsContainer.innerHTML = ''; // Kosongkan isi sebelumnya
    let total = 0;

    cart.forEach((item, index) => {
        total += item.price * item.quantity;

        const listItem = document.createElement('li');
        listItem.innerHTML = `
            <img src="${item.image}" alt="${item.name}" style="width: 90px; height: 90px; margin-right: 30px;">
            ${item.name} - Rp ${item.price} x ${item.quantity}
            <button onclick="addQuantity(${index})" style="margin-left: 5px; background-color: green; color: white;">+</button>
            <button onclick="removeFromCart(${index})" style="margin-left: 5px; color: white;">-</button>
        `;
        listItem.style.display = 'flex';
        listItem.style.alignItems = 'center';
        listItem.style.marginBottom = '10px';

        cartItemsContainer.appendChild(listItem);
    });

    cartTotalContainer.textContent = total; // Update total harga
}
function addQuantity(index) {
    cart[index].quantity += 1; // Tambah jumlah quantity
    renderCart(); // Update tampilan keranjang
}






// pemesanan
function selectPayment(method) {
    document.querySelectorAll('.payment-options .option').forEach(el => {
        el.classList.remove('selected');
    });
    document.getElementById(method.toLowerCase()).classList.add('selected');
}

function selectShipping(method) {
    document.querySelectorAll('.shipping-options .option').forEach(el => {
        el.classList.remove('selected');
    });
    document.getElementById(method.toLowerCase()).classList.add('selected');
}
function processCheckout() {
    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();

    if (!name || !phone || !address) {
        alert('Harap isi semua data alamat pengiriman.');
        return;
    }

    alert('Terima kasih, pesanan Anda telah diproses!');
    localStorage.removeItem('cart');
    localStorage.removeItem('total');
    window.location.href = 'index.html';
}









// Toggle Cart Tab
document.getElementById('shopping-cart-button').addEventListener('click', () => {
    const cartTab = document.getElementById('cart-tab');
    cartTab.style.right = cartTab.style.right === '0px' ? '-650px' : '0px';
});

// Event listener untuk tombol tutup (X)
document.getElementById('close-cart-btn').addEventListener('click', () => {
    document.getElementById('cart-tab').style.right = '-650px';
});
