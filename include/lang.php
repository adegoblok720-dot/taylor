<?php
session_start();

// Set default language
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'id';
$_SESSION['lang'] = $lang;

// Array terjemahan
$translations = [
    'id' => [
        'beranda' => 'Beranda',
        'kategori_produk' => 'Kategori Produk',
        'profil' => 'Profil',
        'pesanan_saya' => 'Pesanan Saya',
        'kontak' => 'Kontak',
        'logout' => 'Logout',
        'notifikasi' => 'Notifikasi',
        'bantuan' => 'Bantuan',
        'bahasa' => 'Bahasa',
        'cari_produk' => 'Cari produk...',
        'checkout' => 'Checkout',
        'jumlah' => 'Jumlah',
        'ukuran' => 'Ukuran',
        'catatan' => 'Catatan',
        'pesan_sekarang' => 'Pesan Sekarang',
        'batal' => 'Batal',
        'stok_tidak_cukup' => 'Stok tidak mencukupi!',
        'harga' => 'Harga',
        'produk_tidak_ditemukan' => 'Produk tidak ditemukan!',
    ],
    'en' => [
        'beranda' => 'Home',
        'kategori_produk' => 'Product Category',
        'profil' => 'Profile',
        'pesanan_saya' => 'My Orders',
        'kontak' => 'Contact',
        'logout' => 'Logout',
        'notifikasi' => 'Notifications',
        'bantuan' => 'Help',
        'bahasa' => 'Language',
        'cari_produk' => 'Search products...',
        'checkout' => 'Checkout',
        'jumlah' => 'Quantity',
        'ukuran' => 'Size',
        'catatan' => 'Note',
        'pesan_sekarang' => 'Order Now',
        'batal' => 'Cancel',
        'stok_tidak_cukup' => 'Stock is insufficient!',
        'harga' => 'Price',
        'produk_tidak_ditemukan' => 'Product not found!',
    ]
];

// Fungsi terjemahan
if (!function_exists('t')) {
    function t($key)
    {
        global $translations, $lang;
        return $translations[$lang][$key] ?? $key;
    }
}
