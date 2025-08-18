<?php
require_once 'database.php';

class LoaiHang {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM loaihang");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM loaihang WHERE idloaihang = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function add($ten, $mota) {
        $stmt = $this->db->prepare("INSERT INTO loaihang (tenloaihang, mota) VALUES (?, ?)");
        return $stmt->execute([$ten, $mota]);
    }

    public function update($id, $tenloaihang, $mota) {
        $stmt = $this->db->prepare("UPDATE loaihang SET tenloaihang = ?, mota = ? WHERE idloaihang = ?");
        return $stmt->execute([$tenloaihang, $mota, $id]);
    }

    public function delete($id) {
        if ($this->getById($id)) {
            $stmt = $this->db->prepare("DELETE FROM loaihang WHERE idloaihang = ?");
            return $stmt->execute([$id]);
        }
        return false;
    }
}
?>
