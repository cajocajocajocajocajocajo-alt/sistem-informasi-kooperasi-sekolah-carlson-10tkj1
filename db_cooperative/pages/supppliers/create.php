<?php
require '../../config/database.php';

$errors = [];
$supplier_name = '';
$email = '';
$phone = '';
$address = '';
$product_types = [];
$partnership_date = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = trim($_POST['supplier_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $product_types = $_POST['product_types'] ?? [];
    $partnership_date = $_POST['partnership_date'] ?? '';

    if ($supplier_name === '') $errors[] = "Nama pemasok wajib diisi.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email wajib diisi dengan format yang benar.";
    }
    if ($phone === '') $errors[] = "Nomor telepon wajib diisi.";
    if ($address === '') $errors[] = "Alamat wajib diisi.";
    if (empty($product_types)) $errors[] = "Pilih minimal satu jenis produk.";
    if ($partnership_date === '') $errors[] = "Tanggal kerjasama wajib diisi.";

    if (empty($errors)) {
        $json_types = json_encode($product_types);

        $sql = "INSERT INTO suppliers (supplier_name, email, phone, address, product_types, partnership_date) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$supplier_name, $email, $phone, $address, $json_types, $partnership_date]);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah pemasok</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>

    <?php include '../../components/navbar.php'; ?>

    <div class="main-content">
        <h2>Tambah pemasok</h2>
        <br>
        <a href="index.php" class="btn-outline">Kembali</a>
        <br><br>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama pemasok</label>
                <input type="text" name="supplier_name" maxlength="100" value="<?= htmlspecialchars($supplier_name) ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" maxlength="100" value="<?= htmlspecialchars($email) ?>">
            </div>

            <div class="form-group">
                <label>Nomor telepon</label>
                <input type="text" name="phone" maxlength="20" value="<?= htmlspecialchars($phone) ?>">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="address" rows="3"><?= htmlspecialchars($address) ?></textarea>
            </div>

            <div class="form-group">
                <label>Jenis produk</label>
                <label style="font-weight: normal;"><input type="checkbox" name="product_types[]" value="Food" <?= in_array('Food', $product_types) ? 'checked' : '' ?>> Food</label>
                <label style="font-weight: normal;"><input type="checkbox" name="product_types[]" value="Drink" <?= in_array('Drink', $product_types) ? 'checked' : '' ?>> Drink</label>
                <label style="font-weight: normal;"><input type="checkbox" name="product_types[]" value="Stationery" <?= in_array('Stationery', $product_types) ? 'checked' : '' ?>> Stationery</label>
                <label style="font-weight: normal;"><input type="checkbox" name="product_types[]" value="Uniform" <?= in_array('Uniform', $product_types) ? 'checked' : '' ?>> Uniform</label>
            </div>

            <div class="form-group">
                <label>Tanggal kerjasama</label>
                <input type="date" name="partnership_date" value="<?= htmlspecialchars($partnership_date) ?>">
            </div>

            <button type="submit" class="btn">Simpan</button>
        </form>
    </div>

</body>
</html>
