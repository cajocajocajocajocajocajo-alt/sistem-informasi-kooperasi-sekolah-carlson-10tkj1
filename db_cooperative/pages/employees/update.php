<?php
require '../../config/database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM employees WHERE employee_id = ?");
$stmt->execute([$id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    header("Location: index.php");
    exit;
}

$errors = [];
$employee_number = $employee['employee_number'];
$full_name = $employee['full_name'];
$gender = $employee['gender'];
$birth_date = $employee['birth_date'];
$position = $employee['position'];
$salary = $employee['salary'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_number = trim($_POST['employee_number'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $birth_date = $_POST['birth_date'] ?? '';
    $position = $_POST['position'] ?? '';
    $salary = $_POST['salary'] ?? '';

    if ($employee_number === '') $errors[] = "Nomor karyawan wajib diisi.";
    if ($full_name === '') $errors[] = "Nama lengkap wajib diisi.";
    if ($gender === '') $errors[] = "Jenis kelamin wajib dipilih.";
    if ($birth_date === '') $errors[] = "Tanggal lahir wajib diisi.";
    if ($position === '') $errors[] = "Posisi wajib dipilih.";
    if ($salary === '' || $salary < 0) $errors[] = "Gaji wajib diisi dan tidak boleh negatif.";
    if ($employee_number !== '') {
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE employee_number = ? AND employee_id != ?");
        $stmtCheck->execute([$employee_number, $id]);
        if ($stmtCheck->fetchColumn() > 0) {
            $errors[] = "Nomor karyawan sudah digunakan karyawan lain.";
        }
    }
    if (empty($errors)) {
        $sql = "UPDATE employees 
                SET employee_number = ?, full_name = ?, gender = ?, birth_date = ?, position = ?, salary = ? 
                WHERE employee_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$employee_number, $full_name, $gender, $birth_date, $position, $salary, $id]);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Karyawan</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <?php include '../../components/navbar.php'; ?>
    <div class="main-content">
        <h2>Ubah karyawan</h2>
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
                <label>Employee number</label>
                <input type="text" name="employee_number" maxlength="20" value="<?= htmlspecialchars($employee_number) ?>">
            </div>
            <div class="form-group">
                <label>Nama lengkap</label>
                <input type="text" name="full_name" maxlength="100" value="<?= htmlspecialchars($full_name) ?>">
            </div>
            <div class="form-group">
                <label>Gender</label>
                <label style="font-weight: normal;"><input type="radio" name="gender" value="L" <?= $gender === 'L' ? 'checked' : '' ?>> Laki-laki (L)</label>
                <label style="font-weight: normal;"><input type="radio" name="gender" value="P" <?= $gender === 'P' ? 'checked' : '' ?>> Perempuan (P)</label>
            </div>
            <div class="form-group">
                <label>Tanggal lahir</label>
                <input type="date" name="birth_date" value="<?= htmlspecialchars($birth_date) ?>">
            </div>
            <div class="form-group">
                <label>Posisi</label>
                <select name="position">
                    <option value="">-- Pilih posisi --</option>
                    <option value="Kasir" <?= $position === 'Kasir' ? 'selected' : '' ?>>Kasir</option>
                    <option value="Admin" <?= $position === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="Gudang" <?= $position === 'Gudang' ? 'selected' : '' ?>>Gudang</option>
                </select>
            </div>
            <div class="form-group">
                <label>Gaji</label>
                <input type="number" step="0.01" name="salary" value="<?= htmlspecialchars($salary) ?>">
            </div>
            <button type="submit" class="btn">Simpan perubahan</button>
        </form>
    </div>
</body>
</html>