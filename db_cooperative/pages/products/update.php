<?php
require '../../config/database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php");
    exit;
}

$errors = [];
$product_code = $product['product_code'];
$product_name = $product['product_name'];
$category = $product['category'];
$unit = $product['unit'];
$price = $product['price'];
$stock = $product['stock'];
$entry_date = $product['entry_date'];
$description = $product['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_code = trim($_POST['product_code'] ?? '');
    $product_name = trim($_POST['product_name'] ?? '');
    $category = $_POST['category'] ?? '';
    $unit = $_POST['unit'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $entry_date = $_POST['entry_date'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($product_code === '') $errors[] = "Kode produk wajib diisi.";
    if ($product_name === '') $errors[] = "Nama produk wajib diisi.";
    if ($category === '') $errors[] = "Kategori wajib dipilih.";
    if ($unit === '') $errors[] = "Satuan wajib dipilih.";
    if ($price === '' || $price < 0) $errors[] = "Harga wajib diisi dan tidak boleh negatif.";
    if ($stock === '' || $stock < 0) $errors[] = "Stok wajib diisi dan tidak boleh negatif.";
    if ($entry_date === '') $errors[] = "Tanggal masuk wajib diisi.";
    if ($description === '') $errors[] = "Deskripsi wajib diisi.";

    if ($product_code !== '') {
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM products WHERE product_code = ? AND product_id != ?");
        $stmtCheck->execute([$product_code, $id]);
        if ($stmtCheck->fetchColumn() > 0) {
            $errors[] = "Kode produk sudah digunakan produk lain.";
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE products 
                SET product_code = ?, product_name = ?, category = ?, unit = ?, price = ?, stock = ?, entry_date = ?, description = ? 
                WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_code, $product_name, $category, $unit, $price, $stock, $entry_date, $description, $id]);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah produk</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>

    <?php include '../../components/navbar.php'; ?>

    <div class="main-content">
        <h2>Ubah produk</h2>
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
                <label>Kode produk</label>
                <input type="text" name="product_code" maxlength="10" value="<?= htmlspecialchars($product_code) ?>">
            </div>

            <div class="form-group">
                <label>Nama produk</label>
                <input type="text" name="product_name" maxlength="100" value="<?= htmlspecialchars($product_name) ?>">
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category">
                    <option value="">-- Pilih kategori --</option>
                    <option value="Food" <?= $category === 'Food' ? 'selected' : '' ?>>Food</option>
                    <option value="Drink" <?= $category === 'Drink' ? 'selected' : '' ?>>Drink</option>
                    <option value="Stationery" <?= $category === 'Stationery' ? 'selected' : '' ?>>Stationery</option>
                    <option value="Uniform" <?= $category === 'Uniform' ? 'selected' : '' ?>>Uniform</option>
                </select>
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <label style="font-weight: normal;"><input type="radio" name="unit" value="Pcs" <?= $unit === 'Pcs' ? 'checked' : '' ?>> Pcs</label>
                <label style="font-weight: normal;"><input type="radio" name="unit" value="Pack" <?= $unit === 'Pack' ? 'checked' : '' ?>> Pack</label>
                <label style="font-weight: normal;"><input type="radio" name="unit" value="Bottle" <?= $unit === 'Bottle' ? 'checked' : '' ?>> Bottle</label>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>">
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>">
            </div>

            <div class="form-group">
                <label>Tanggal masuk</label>
                <input type="date" name="entry_date" value="<?= htmlspecialchars($entry_date) ?>">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>
            </div>

            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>
