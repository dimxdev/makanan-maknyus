document.addEventListener('DOMContentLoaded', () => {
    // Ambil data keranjang dari localStorage atau gunakan data contoh
    const cart = JSON.parse(localStorage.getItem('cart')) || [
        { id: 1, name: "Item A", price: 20000, quantity: 2 },
        { id: 2, name: "Item B", price: 50000, quantity: 1 }
    ];

    let total = 0;

    // Elemen untuk menampilkan data keranjang dan total harga
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalContainer = document.getElementById('cart-total'); // Total atas
    const finalTotalContainer = document.getElementById('final-total'); // Total bawah

    // Ambil data pengguna yang sudah login dari atribut data- di HTML
    const nameField = document.getElementById('name');
    const phoneField = document.getElementById('phone');
    const addressField = document.getElementById('address');

    const user = {
        name: nameField.getAttribute('data-name'),
        phone: phoneField.getAttribute('data-phone'),
        address: addressField.getAttribute('data-address')
    };

    // Fungsi untuk memperbarui tampilan keranjang
    function updateCart() {
        cartItemsContainer.innerHTML = ""; // Kosongkan elemen sebelumnya

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<li>Keranjang kosong!</li>';
        } else {
            cart.forEach(item => {
                const listItem = document.createElement('li');
                listItem.innerHTML = `
                    <img src="${item.image || 'https://via.placeholder.com/150'}" alt="${item.name}" style="width: 150px; height: 150px; margin-right: 40px;">
                    ${item.name} - Rp ${item.price} x ${item.quantity}
                `;
                listItem.style.display = 'flex';
                listItem.style.alignItems = 'center';
                listItem.style.marginBottom = '40px';
                cartItemsContainer.appendChild(listItem);

                // Hitung total harga
                total += item.price * item.quantity;
            });
        }

        // Simpan total harga ke localStorage
        localStorage.setItem('total', total);

        // Tampilkan total harga di kedua elemen
        cartTotalContainer.textContent = total;
        finalTotalContainer.textContent = total;
    }

    // Fungsi untuk memproses checkout
    function processCheckout() {
        document.getElementById('cartItemsData').value = JSON.stringify(cart);
        document.getElementById('address-form').submit();
    }

    // Fungsi untuk memilih metode pembayaran
    function selectPayment(method) {
        document.getElementById('paymentMethod').value = method;

        // Hapus kelas 'selected' dari semua opsi
        paymentOptions.forEach(option => option.classList.remove('selected'));

        // Tambahkan kelas 'selected' pada opsi yang dipilih
        const selectedOption = document.getElementById(method); // Gunakan ID sesuai dengan HTML
        if (selectedOption) {
            selectedOption.classList.add('selected');
        }
    }

    // Fungsi untuk memilih metode pengiriman
    function selectShippingMethod(element) {
        document.getElementById('shippingMethod').value = element.value;
    }

    // Perbarui keranjang saat halaman dimuat
    updateCart();

    // Tambahkan event listener untuk memilih metode pengiriman
    document.getElementById('shipping-method').addEventListener('change', (event) => {
        selectShippingMethod(event.target);
    });

    // Tambahkan event listener untuk metode pembayaran
    const paymentOptions = document.querySelectorAll('.payment-options .option');
    paymentOptions.forEach(option => {
        option.addEventListener('click', () => {
            const method = option.id; // Ambil ID sesuai dengan HTML
            selectPayment(method);
        });
    });
});
