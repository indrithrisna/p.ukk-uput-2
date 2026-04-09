<?php
class PS {
    private $conn;
    private $table_name = "ps";

    public $id;
    public $nama_ps;
    public $tipe;
    public $status;
    public $harga_per_jam;
    public $created_at;

    // Menyimpan instance koneksi database ke properti model.
    public function __construct($db) {
        $this->conn = $db;
    }

    // Menambahkan data PS baru.
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nama_ps, tipe, status, harga_per_jam) VALUES (:nama_ps, :tipe, :status, :harga_per_jam)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nama_ps", $this->nama_ps);
        $stmt->bindParam(":tipe", $this->tipe);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":harga_per_jam", $this->harga_per_jam);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mengambil seluruh data PS.
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mengambil PS yang statusnya tersedia untuk dipinjam.
    public function readAvailable() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 'tersedia' ORDER BY nama_ps ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mengambil satu data PS berdasarkan id.
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Memperbarui data utama PS.
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_ps = :nama_ps, tipe = :tipe, status = :status, harga_per_jam = :harga_per_jam WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nama_ps", $this->nama_ps);
        $stmt->bindParam(":tipe", $this->tipe);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":harga_per_jam", $this->harga_per_jam);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Memperbarui status PS saja (tersedia/dipinjam).
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus data PS berdasarkan id.
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
