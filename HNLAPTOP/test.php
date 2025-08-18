<?php
require_once 'mod/config.php';

// Test với ID 18
$id = 18;
$stmt = $pdo->prepare("SELECT * FROM danhgia WHERE id = ?");
$stmt->execute([$id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($result);
echo "</pre>";
?>