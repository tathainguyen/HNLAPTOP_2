<?php
require_once 'database.php';

class HangHoa {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); // Lấy kết nối từ singleton Database
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM hanghoa");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM hanghoa WHERE idhanghoa = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function add($ten, $mota, $gia, $loai, $hinhanh = null) {
        $stmt = $this->db->prepare("INSERT INTO hanghoa (tenhanghoa, mota, giathamkhao, idloaihang, hinhanh) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$ten, $mota, $gia, $loai, $hinhanh]);
    }
    
    

    public function delete($id) {
        if ($this->getById($id)) { // Kiểm tra trước khi xóa
            $stmt = $this->db->prepare("DELETE FROM hanghoa WHERE idhanghoa = ?");
            return $stmt->execute([$id]);
        }
        return false;
    }

    public function getByFilters($idloaihang = null, $min_price = 0, $max_price = 100000000) {
        $sql = "SELECT * FROM hanghoa WHERE giathamkhao BETWEEN :min_price AND :max_price";
        
        if ($idloaihang !== null) {
            $sql .= " AND idloaihang = :idloaihang";
        }
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':min_price', $min_price, PDO::PARAM_INT);
        $stmt->bindParam(':max_price', $max_price, PDO::PARAM_INT);
    
        if ($idloaihang !== null) {
            $stmt->bindParam(':idloaihang', $idloaihang, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByLoai($idloaihang) {
        $stmt = $this->db->prepare("SELECT * FROM hanghoa WHERE idloaihang = ?");
        $stmt->execute([$idloaihang]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    
    public function update($id, $ten, $mota, $gia, $loai, $hinhanh = null) {
        if ($hinhanh) {
            $sql = "UPDATE hanghoa SET tenhanghoa = ?, mota = ?, giathamkhao = ?, idloaihang = ?, hinhanh = ? WHERE idhanghoa = ?";
            $params = [$ten, $mota, $gia, $loai, $hinhanh, $id];
        } else {
            $sql = "UPDATE hanghoa SET tenhanghoa = ?, mota = ?, giathamkhao = ?, idloaihang = ? WHERE idhanghoa = ?";
            $params = [$ten, $mota, $gia, $loai, $id];
        }
    
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

 

    // Lấy tất cả sản phẩm với giới hạn và offset
    public function getAllWithLimit($limit, $offset) {
        $sql = "SELECT * FROM hanghoa LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy sản phẩm theo loại với giới hạn và offset
    public function getByLoaiWithLimit($idloaihang, $limit, $offset) {
        $sql = "SELECT * FROM hanghoa WHERE idloaihang = ? LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $idloaihang, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Đếm tổng số sản phẩm
    public function countAll() {
        $sql = "SELECT COUNT(*) FROM hanghoa";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Đếm số sản phẩm theo loại
    public function countByLoai($idloaihang) {
        $sql = "SELECT COUNT(*) FROM hanghoa WHERE idloaihang = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idloaihang]);
        return $stmt->fetchColumn();
    }
    
    public function updateChiTiet($id, $chitiet) {
        $stmt = $this->db->prepare("UPDATE hanghoa SET chitiet = ? WHERE idhanghoa = ?");
        return $stmt->execute([$chitiet, $id]);
    }

    
    
    
    
    
}
?>
