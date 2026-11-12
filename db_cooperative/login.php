<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: pages/products/index.php");
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($email === 'admin@gmail.com' && $password === '123') {
        $_SESSION['login'] = true;
        header("Location: pages/products/index.php");
        exit;
    } else {
        $error = "Email atau kata sandi salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
    <div style="width: 350px; padding: 25px; border: 1px solid #000000; background-color: #ffffff;">
        <h2 style="margin-bottom: 20px;">Masuk ke Koperasi</h2>
        <?php if ($error !== ''): ?>
            <div class="alert-error" style="margin-bottom: 15px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" style="width: 100%; padding: 8px; border: 1px solid #000000;" required>
            </div>
            <div class="form-group">
                <label>Kata sandi</label>
                <input type="password" name="password" style="width: 100%; padding: 8px; border: 1px solid #000000;" required>
            </div>
            <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Masuk</button>
        </form>
    </div>
    <footer>
        <p>
            hint: admin@gmail.com, 123
        </p>
    </footer>
</body>
</html>
