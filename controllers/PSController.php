<?php
require_once 'models/PS.php';

class PSController {
    private $db;
    private $ps;

    // Inisialisasi koneksi database dan model PS.
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->ps = new PS($this->db);
    }

    // Router aksi untuk modul PS (admin dan petugas).
    public function handleRequest($action) {
        AuthController::checkRole(['admin', 'petugas']);
        
        switch($action) {
            case 'index':
                $this->index();
                break;
            case 'create':
                $this->create();
                break;
            case 'store':
                $this->store();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                $this->index();
                break;
        }
    }

    // Menampilkan daftar data PS.
    private function index() {
        $stmt = $this->ps->read();
        require_once 'views/ps/index.php';
    }

    // Menampilkan form tambah PS.
    private function create() {
        require_once 'views/ps/create.php';
    }

    // Menyimpan data PS baru.
    private function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ps->nama_ps = $_POST['nama_ps'];
            $this->ps->tipe = $_POST['tipe'];
            $this->ps->status = $_POST['status'];
            $this->ps->harga_per_jam = $_POST['harga_per_jam'];

            if($this->ps->create()) {
                // Log aktivitas
                require_once 'controllers/ActivityLogController.php';
                ActivityLogController::log('create', 'Menambahkan PS baru: ' . $_POST['nama_ps'] . ' (' . $_POST['tipe'] . ')');
                
                $_SESSION['success'] = "PS berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan PS!";
            }
            header('Location: index.php?page=ps&action=index');
            exit();
        }
    }

    // Menampilkan form edit data PS.
    private function edit() {
        $this->ps->id = $_GET['id'];
        $stmt = $this->ps->readOne();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        require_once 'views/ps/edit.php';
    }

    // Memperbarui data PS berdasarkan input form.
    private function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ps->id = $_POST['id'];
            $this->ps->nama_ps = $_POST['nama_ps'];
            $this->ps->tipe = $_POST['tipe'];
            $this->ps->status = $_POST['status'];
            $this->ps->harga_per_jam = $_POST['harga_per_jam'];

            if($this->ps->update()) {
                // Log aktivitas
                require_once 'controllers/ActivityLogController.php';
                ActivityLogController::log('update', 'Mengupdate PS: ' . $_POST['nama_ps'] . ' (' . $_POST['tipe'] . ')');
                
                $_SESSION['success'] = "PS berhasil diupdate!";
            } else {
                $_SESSION['error'] = "Gagal mengupdate PS!";
            }
            header('Location: index.php?page=ps&action=index');
            exit();
        }
    }

    // Menghapus data PS berdasarkan id.
    private function delete() {
        if(isset($_GET['id'])) {
            // Cek apakah PS sedang dipinjam
            $this->ps->id = $_GET['id'];
            $stmt = $this->ps->readOne();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if($data && $data['status'] == 'dipinjam') {
                $_SESSION['error'] = "PS tidak bisa dihapus karena sedang dipinjam!";
                header('Location: index.php?page=ps&action=index');
                exit();
            }
            if($this->ps->delete()) {
                require_once 'controllers/ActivityLogController.php';
                ActivityLogController::log('delete', 'Menghapus PS id: ' . $_GET['id']);
                $_SESSION['success'] = "PS berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus PS!";
            }
        }
        header('Location: index.php?page=ps&action=index');
        exit();
    }
}
