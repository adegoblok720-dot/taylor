<?php
session_start();
include '../include/db.php';

// Bahasa
$lang = $_GET['lang'] ?? 'id';
$langs = [
    'id' => [
        'title' => 'Kontak',
        'name' => 'Nama',
        'email' => 'Email',
        'subject' => 'Subjek',
        'message' => 'Pesan',
        'send' => 'Kirim Pesan',
        'success' => 'Pesan berhasil dikirim!',
        'fail' => 'Terjadi kesalahan, coba lagi.'
    ],
    'en' => [
        'title' => 'Contact',
        'name' => 'Name',
        'email' => 'Email',
        'subject' => 'Subject',
        'message' => 'Message',
        'send' => 'Send Message',
        'success' => 'Message sent successfully!',
        'fail' => 'An error occurred, please try again.'
    ]
];
$t = $langs[$lang];

// Kirim pesan
$status_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama'] ?? '';
    $email  = $_POST['email'] ?? '';
    $subjek = $_POST['subjek'] ?? '';
    $pesan  = $_POST['pesan'] ?? '';

    if ($nama && $email && $subjek && $pesan) {
        $stmt = $conn->prepare("INSERT INTO kontak (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $email, $subjek, $pesan);
        if ($stmt->execute()) {
            $status_msg = '<div class="alert alert-success">' . $t['success'] . '</div>';
        } else {
            $status_msg = '<div class="alert alert-danger">' . $t['fail'] . '</div>';
        }
        $stmt->close();
    } else {
        $status_msg = '<div class="alert alert-danger">' . $t['fail'] . '</div>';
    }
}
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

    <div class="container mt-5" style="max-width:600px;">
        <h2 class="text-center mb-4"><?= $t['title']; ?></h2>

        <?= $status_msg; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label"><?= $t['name']; ?></label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><?= $t['email']; ?></label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><?= $t['subject']; ?></label>
                <input type="text" name="subjek" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><?= $t['message']; ?></label>
                <textarea name="pesan" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= $t['send']; ?></button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>