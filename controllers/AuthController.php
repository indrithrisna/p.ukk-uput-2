<?php
require_once 'models/User.php';

class AuthController {
    private $db;
    private $user;

    // Inisialisasi koneksi database dan model user.
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    // Menampilkan halaman login jika user belum login.
    public function showLogin() {
        if(isset($_SESSION['user_id'])) {
            header('Location: index.php?page=dashboard');
            exit();
        }
        require_once 'views/login.php';
    }

    // Memproses autentikasi login dan menyimpan session user.
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $this->user->login();
            $num = $stmt->rowCount();

            if($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['nama'] = $row['nama'];
                    $_SESSION['role'] = $row['role'];
                    
                    // Log aktivitas login
                    require_once 'controllers/ActivityLogController.php';
                    ActivityLogController::log('login', 'User login ke sistem');
                    
                    header('Location: index.php?page=dashboard');
                    exit();
                } else {
                    $_SESSION['error'] = "Password salah!";
                    header('Location: index.php?page=login');
                    exit();
                }
            } else {
                $_SESSION['error'] = "Username tidak ditemukan!";
                header('Location: index.php?page=login');
                exit();
            }
        }
    }

    // Logout user aktif lalu menghapus session.
    public function logout() {
        // Log aktivitas logout
        require_once 'controllers/ActivityLogController.php';
        ActivityLogController::log('logout', 'User logout dari sistem');
        
        session_destroy();
        header('Location: index.php?page=login');
        exit();
    }

    // Memastikan user sudah login sebelum mengakses halaman terproteksi.
    public static function checkAuth() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
    }

    // Memastikan role user termasuk dalam daftar role yang diizinkan.
    public static function checkRole($allowed_roles) {
        self::checkAuth();
        if(!in_array($_SESSION['role'], $allowed_roles)) {
            $_SESSION['error'] = "Anda tidak memiliki akses ke halaman ini!";
            header('Location: index.php?page=dashboard');
            exit();
        }
    }
}
