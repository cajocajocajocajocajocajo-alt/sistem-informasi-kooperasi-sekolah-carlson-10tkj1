<?php
require '../../config/database.php';

$stmt = $pdo->query("SELECT * FROM suppliers ORDER BY supplier_id DESC");
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data suppliers</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>

    <?php include '../../components/navbar.php'; ?>

    <div class="main-content">
        <h2>Data supplier</h2>
        <br>
        <a href="create.php" class="btn">+ Tambah pemasok</a>

        <table>
            <thead>
                <tr>
                    <th>Nama pemasok</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Jenis produk</th>
                    <th>Tgl kerjasama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($suppliers) > 0): ?>
                    <?php foreach ($suppliers as $row): ?>
                        <?php
                        $types = json_decode($row['product_types'], true);
                        $types_text = is_array($types) ? implode(', ', $types) : $row['product_types'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['supplier_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($types_text) ?></td>
                            <td><?= htmlspecialchars($row['partnership_date']) ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['supplier_id'] ?>" class="btn-outline">Ubah</a>
                                <a href="delete.php?id=<?= $row['supplier_id'] ?>" class="btn-outline" onclick="return confirm('Hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Belum ada data pemasok.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
