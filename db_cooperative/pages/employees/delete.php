<?php
require '../../config/database.php';
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM employees WHERE employee_id = ?");
    $stmt->execute([$id]);
}
header("Location: index.php");
exit;
?>