<?php
class Kategori {
    private $conn;
    private $table_name = "kategori";

    public $id;
    public $nama_kategori;
    public $deskripsi;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nama_kategori, deskripsi) VALUES (:nama_kategori, :deskripsi)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama_kategori", $this->nama_kategori);
        $stmt->bindParam(":deskripsi", $this->deskripsi);
        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_kategori = :nama_kategori, deskripsi = :deskripsi WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama_kategori", $this->nama_kategori);
        $stmt->bindParam(":deskripsi", $this->deskripsi);
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
