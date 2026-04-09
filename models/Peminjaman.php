<?php
class Peminjaman {
    private $conn;
    private $table_name = "peminjaman";

    public $id;
    public $user_id;
    public $no_ktp;
    public $no_telepon;
    public $ps_id;
    public $tanggal_pinjam;
    public $tanggal_kembali;
    public $kondisi_ps;
    public $denda;
    public $keterangan;
    public $durasi_jam;
    public $total_harga;
    public $status;
    public $approved_by;
    public $approved_at;
    public $alasan_tolak;
    public $created_at;

    // Menyimpan instance koneksi database ke properti model.
    public function __construct($db) {
        $this->conn = $db;
    }

    // Menambahkan data pengajuan peminjaman baru.
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, no_ktp, no_telepon, ps_id, tanggal_pinjam, durasi_jam, total_harga, status) VALUES (:user_id, :no_ktp, :no_telepon, :ps_id, :tanggal_pinjam, :durasi_jam, :total_harga, :status)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":no_ktp", $this->no_ktp);
        $stmt->bindParam(":no_telepon", $this->no_telepon);
        $stmt->bindParam(":ps_id", $this->ps_id);
        $stmt->bindParam(":tanggal_pinjam", $this->tanggal_pinjam);
        $stmt->bindParam(":durasi_jam", $this->durasi_jam);
        $stmt->bindParam(":total_harga", $this->total_harga);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mengambil seluruh data peminjaman beserta relasi user dan PS.
    public function read() {
        $query = "SELECT p.*, u.nama as nama_peminjam, ps_table.nama_ps, ps_table.tipe,
                  approver.nama as approved_by_nama
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.user_id = u.id
                  LEFT JOIN ps ps_table ON p.ps_id = ps_table.id
                  LEFT JOIN users approver ON p.approved_by = approver.id
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mengambil data peminjaman berdasarkan user yang sedang login.
    public function readByUser() {
        $query = "SELECT p.*, ps_table.nama_ps, ps_table.tipe 
                  FROM " . $this->table_name . " p
                  LEFT JOIN ps ps_table ON p.ps_id = ps_table.id
                  WHERE p.user_id = :user_id
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    // Mengambil satu data peminjaman berdasarkan id.
    public function readOne() {
        $query = "SELECT p.*, u.nama as nama_peminjam, ps_table.nama_ps, ps_table.tipe, ps_table.harga_per_jam
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.user_id = u.id
                  LEFT JOIN ps ps_table ON p.ps_id = ps_table.id
                  WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Memperbarui data inti peminjaman (PS, tanggal, durasi, total harga).
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET ps_id = :ps_id, tanggal_pinjam = :tanggal_pinjam, durasi_jam = :durasi_jam, total_harga = :total_harga WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":ps_id", $this->ps_id);
        $stmt->bindParam(":tanggal_pinjam", $this->tanggal_pinjam);
        $stmt->bindParam(":durasi_jam", $this->durasi_jam);
        $stmt->bindParam(":total_harga", $this->total_harga);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Memperbarui status peminjaman, termasuk data approval/kembali sesuai status.
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET status = :status";
        
        if($this->status == 'selesai') {
            $query .= ", tanggal_kembali = NOW()";
        }
        
        if($this->status == 'disetujui' || $this->status == 'ditolak') {
            $query .= ", approved_by = :approved_by, approved_at = NOW()";
        }

        if($this->status == 'ditolak') {
            $query .= ", alasan_tolak = :alasan_tolak";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        
        if($this->status == 'disetujui' || $this->status == 'ditolak') {
            $stmt->bindParam(":approved_by", $this->approved_by);
        }
        if($this->status == 'ditolak') {
            $stmt->bindParam(":alasan_tolak", $this->alasan_tolak);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus data peminjaman berdasarkan id.
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menyimpan data pengembalian: tanggal kembali, kondisi PS, denda, dan keterangan.
    public function updatePengembalian() {
        $query = "UPDATE " . $this->table_name . " 
                  SET tanggal_kembali = NOW(), 
                      kondisi_ps = :kondisi_ps, 
                      denda = :denda, 
                      keterangan = :keterangan,
                      status = 'selesai'
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kondisi_ps", $this->kondisi_ps);
        $stmt->bindParam(":denda", $this->denda);
        $stmt->bindParam(":keterangan", $this->keterangan);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
