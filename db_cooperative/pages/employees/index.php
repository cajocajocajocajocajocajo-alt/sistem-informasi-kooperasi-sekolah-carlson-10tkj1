<?php
require '../../config/database.php';
$stmt = $pdo->query("SELECT * FROM employees ORDER BY employee_id DESC");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data employee</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include '../../components/navbar.php'; ?>
    <div class="main-content">
        <h2>Data employees</h2>
        <br>
        <a href="create.php" class="btn">+ Tambah Karyawan</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Number</th>
                    <th>Nama lengkap</th>
                    <th>Gender</th>
                    <th>Tgl lahir</th>
                    <th>Posisi</th>
                    <th>Gaji</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($employees) > 0): ?>
                    <?php foreach ($employees as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['employee_id']) ?></td>
                            <td><?= htmlspecialchars($row['employee_number']) ?></d>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['gender']) ?></td>
                            <td><?= htmlspecialchars($row['birth_date']) ?></td>
                            <td><?= htmlspecialchars($row['position']) ?></td>
                            <td><?= htmlspecialchars($row['salary']) ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['employee_id'] ?>" class="btn-outline">Ubah</a>
                                <a href="delete.php?id=<?= $row['employee_id'] ?>" class="btn-outline" onclick="return confirm('Hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">Belum ada data karyawan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
