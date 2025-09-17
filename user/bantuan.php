<?php
session_start();
include '../include/db.php';

// Bahasa
$lang = $_GET['lang'] ?? 'id';
$langs = [
    'id' => [
        'title' => 'Bantuan',
        'faq' => 'Pertanyaan Umum',
        'contact_us' => 'Hubungi Kami',
        'question1' => 'Bagaimana cara memesan baju?',
        'answer1' => 'Anda bisa memesan melalui halaman produk dan menambahkan ke keranjang.',
        'question2' => 'Bagaimana melacak pesanan saya?',
        'answer2' => 'Cek di halaman "Pesanan Saya" setelah login.',
        'back_dashboard' => 'Kembali ke Dashboard'
    ],
    'en' => [
        'title' => 'Help',
        'faq' => 'Frequently Asked Questions',
        'contact_us' => 'Contact Us',
        'question1' => 'How to order clothes?',
        'answer1' => 'You can order from the products page and add to your cart.',
        'question2' => 'How to track my orders?',
        'answer2' => 'Check in "My Orders" page after login.',
        'back_dashboard' => 'Back to Dashboard'
    ]
];
$t = $langs[$lang];
?>
<!doctype html>
<html lang="<?= $lang; ?>">

<head>
    <meta charset="utf-8">
    <title><?= $t['title']; ?> - Tailor Baju</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar_user.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4"><?= $t['title']; ?></h2>

        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        <?= $t['question1']; ?>
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body"><?= $t['answer1']; ?></div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        <?= $t['question2']; ?>
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                    <div class="accordion-body"><?= $t['answer2']; ?></div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="user_dashboard.php" class="btn btn-primary"><?= $t['back_dashboard']; ?></a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>