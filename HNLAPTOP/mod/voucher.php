<?php
require_once __DIR__ . '/database.php';

class Voucher
{
    public static function getByCode($code)
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("SELECT * FROM voucher WHERE code = ? AND (expired_at IS NULL OR expired_at > NOW()) LIMIT 1");
        $stmt->execute([$code]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ?: null;
    }
}
?>