<?php
class Alat {
    private $conn;
    private $table_name = "alat";

    public $id;
    public $kategori_id;
    public $nama_alat;
    public $jumlah;
    public $kondisi;
    public $keterangan;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readByKategori() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE kategori_id = :kategori_id ORDER BY nama_alat ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kategori_id", $this->kategori_id);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT a.*, k.nama_kategori FROM " . $this->table_name . " a
                  JOIN kategori k ON a.kategori_id = k.id
                  WHERE a.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (kategori_id, nama_alat, jumlah, kondisi, keterangan) VALUES (:kategori_id, :nama_alat, :jumlah, :kondisi, :keterangan)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kategori_id", $this->kategori_id);
        $stmt->bindParam(":nama_alat", $this->nama_alat);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":kondisi", $this->kondisi);
        $stmt->bindParam(":keterangan", $this->keterangan);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_alat = :nama_alat, jumlah = :jumlah, kondisi = :kondisi, keterangan = :keterangan WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama_alat", $this->nama_alat);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":kondisi", $this->kondisi);
        $stmt->bindParam(":keterangan", $this->keterangan);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
