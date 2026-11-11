<?php
require '../../config/database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
    header("Location: index.php");
    exit;
}

$errors = [];
$supplier_name = $supplier['supplier_name'];
$email = $supplier['email'];
$phone = $supplier['phone'];
$address = $supplier['address'];
$product_types = json_decode($supplier['product_types'], true) ?? [];
$partnership_date = $supplier['partnership_date'];

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

        $sql = "UPDATE suppliers 
                SET supplier_name = ?, email = ?, phone = ?, address = ?, product_types = ?, partnership_date = ? 
                WHERE supplier_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$supplier_name, $email, $phone, $address, $json_types, $partnership_date, $id]);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Pemasok</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>

    <?php include '../../components/navbar.php'; ?>

    <div class="main-content">
        <h2>Ubah Pemasok</h2>
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
                <label>Nama Pemasok</label>
                <input type="text" name="supplier_name" maxlength="100" value="<?= htmlspecialchars($supplier_name) ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" maxlength="100" value="<?= htmlspecialchars($email) ?>">
            </div>

            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="phone" maxlength="20" value="<?= htmlspecialchars($phone) ?>">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="address" rows="3"><?= htmlspecialchars($address) ?></textarea>
            </div>

            <div class="form-group">
                <label>Jenis Produk (Bisa pilih lebih dari satu)</label>
                <label style="font-weight: normal;"><input type="checkbox" name="product_types[]" value="Food" <?= in_array('Food', $product_types) ? 'checked' : '' ?>> Food</label>
                <label style="font-weight: normal;"><input type="checkbox" name="product_types[]" value="Drink" <?= in_array('Drink', $product_types) ? 'checked' : '' ?>> Drink</label>
                <label style="font-weight: normal;"><input type="checkbox" name="product_types[]" value="Stationery" <?= in_array('Stationery', $product_types) ? 'checked' : '' ?>> Stationery</label>
                <label style="font-weight: normal;"><input type="checkbox" name="product_types[]" value="Uniform" <?= in_array('Uniform', $product_types) ? 'checked' : '' ?>> Uniform</label>
            </div>

            <div class="form-group">
                <label>Tanggal Kerjasama</label>
                <input type="date" name="partnership_date" value="<?= htmlspecialchars($partnership_date) ?>">
            </div>

            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>
