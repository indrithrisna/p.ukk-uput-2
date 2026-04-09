<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $nama;
    public $role;
    public $created_at;

    // Menyimpan instance koneksi database ke properti model.
    public function __construct($db) {
        $this->conn = $db;
    }

    // Mengambil data user berdasarkan username untuk proses login.
    public function login() {
        $query = "SELECT id, username, password, nama, role FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
        return $stmt;
    }

    // Menambahkan user baru ke tabel users.
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (username, password, nama, role) VALUES (:username, :password, :nama, :role)";
        $stmt = $this->conn->prepare($query);
        
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":role", $this->role);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mengambil seluruh data user untuk ditampilkan di halaman daftar.
    public function read() {
        $query = "SELECT id, username, nama, role, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mengambil satu data user berdasarkan id.
    public function readOne() {
        $query = "SELECT id, username, nama, role FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Memperbarui data user, termasuk password jika diisi.
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET username = :username, nama = :nama, role = :role";
        
        if(!empty($this->password)) {
            $query .= ", password = :password";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":id", $this->id);
        
        if(!empty($this->password)) {
            $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(":password", $hashed_password);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus user berdasarkan id.
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
