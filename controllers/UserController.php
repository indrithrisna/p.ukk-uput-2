<?php
require_once 'models/User.php';

class UserController {
    private $db;
    private $user;

    // Inisialisasi koneksi database dan model user.
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    // Router aksi untuk modul user (khusus admin).
    public function handleRequest($action) {
        AuthController::checkRole(['admin']);
        
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

    // Menampilkan daftar user.
    private function index() {
        $stmt = $this->user->read();
        require_once 'views/user/index.php';
    }

    // Menampilkan form tambah user.
    private function create() {
        require_once 'views/user/create.php';
    }

    // Menyimpan user baru dari form.
    private function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->password = $_POST['password'];
            $inputNama = trim($_POST['nama'] ?? '');
            $isPetugas = ($_POST['role'] ?? '') === 'petugas';
            $this->user->nama = $isPetugas ? $this->user->username : $inputNama;
            $this->user->role = $_POST['role'];

            if($this->user->create()) {
                // Log aktivitas
                require_once 'controllers/ActivityLogController.php';
                ActivityLogController::log('create', 'Menambahkan user baru: ' . ($this->user->nama ?: '-') . ' (' . $_POST['username'] . ')');
                
                $_SESSION['success'] = "User berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan user!";
            }
            header('Location: index.php?page=user&action=index');
            exit();
        }
    }

    // Menampilkan form edit berdasarkan id user.
    private function edit() {
        $this->user->id = $_GET['id'];
        $stmt = $this->user->readOne();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        require_once 'views/user/edit.php';
    }

    // Memperbarui data user berdasarkan input form.
    private function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->id = $_POST['id'];
            $this->user->username = $_POST['username'];
            $this->user->password = $_POST['password'];
            $inputNama = trim($_POST['nama'] ?? '');
            $isPetugas = ($_POST['role'] ?? '') === 'petugas';
            $this->user->nama = $isPetugas ? $this->user->username : $inputNama;
            $this->user->role = $_POST['role'];

            if($this->user->update()) {
                // Log aktivitas
                require_once 'controllers/ActivityLogController.php';
                ActivityLogController::log('update', 'Mengupdate user: ' . ($this->user->nama ?: '-') . ' (' . $_POST['username'] . ')');
                
                $_SESSION['success'] = "User berhasil diupdate!";
            } else {
                $_SESSION['error'] = "Gagal mengupdate user!";
            }
            header('Location: index.php?page=user&action=index');
            exit();
        }
    }

    // Menghapus user berdasarkan id.
    private function delete() {
        if(isset($_GET['id'])) {
            if($_GET['id'] == $_SESSION['user_id']) {
                $_SESSION['error'] = "Tidak bisa menghapus akun sendiri!";
                header('Location: index.php?page=user&action=index');
                exit();
            }
            $this->user->id = $_GET['id'];
            if($this->user->delete()) {
                require_once 'controllers/ActivityLogController.php';
                ActivityLogController::log('delete', 'Menghapus user id: ' . $_GET['id']);
                $_SESSION['success'] = "User berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus user!";
            }
        }
        header('Location: index.php?page=user&action=index');
        exit();
    }
}
