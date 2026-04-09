<?php
require_once 'models/Peminjaman.php';
require_once 'models/PS.php';

class PeminjamanController {
    private $db;
    private $peminjaman;
    private $ps;

    // Inisialisasi koneksi database dan model terkait peminjaman.
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->peminjaman = new Peminjaman($this->db);
        $this->ps = new PS($this->db);
    }

    // Router aksi untuk modul peminjaman.
    public function handleRequest($action) {
        AuthController::checkAuth();
        
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
            case 'approve':
                $this->approve();
                break;
            case 'reject':
                $this->reject();
                break;
            case 'selesai':
                $this->selesai();
                break;
            case 'pengembalian':
                $this->pengembalian();
                break;
            case 'proses_pengembalian':
                $this->prosesPengembalian();
                break;
            default:
                $this->index();
                break;
        }
    }

    // Menampilkan daftar peminjaman (semua data atau data milik peminjam).
    private function index() {
        if($_SESSION['role'] == 'peminjam') {
            $this->peminjaman->user_id = $_SESSION['user_id'];
            $stmt = $this->peminjaman->readByUser();
        } else {
            $stmt = $this->peminjaman->read();
        }
        require_once 'views/peminjaman/index.php';
    }

    // Menampilkan form pengajuan peminjaman (khusus role peminjam).
    private function create() {
        // Hanya peminjam yang bisa ajukan peminjaman
        AuthController::checkRole(['peminjam']);
        
        $stmt = $this->ps->readAvailable();
        require_once 'views/peminjaman/create.php';
    }

    // Menyimpan data pengajuan peminjaman baru.
    private function store() {
        // Hanya peminjam yang bisa ajukan peminjaman
        AuthController::checkRole(['peminjam']);
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi user_id
            if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
                $_SESSION['error'] = "Session expired. Silakan login kembali!";
                header('Location: index.php?page=login');
                exit();
            }
            
            // Validasi input
            if(empty($_POST['ps_id']) || empty($_POST['tanggal_pinjam']) || empty($_POST['durasi_jam']) || empty($_POST['total_harga']) || empty($_POST['no_ktp']) || empty($_POST['no_telepon'])) {
                $_SESSION['error'] = "Semua field harus diisi!";
                header('Location: index.php?page=peminjaman&action=create');
                exit();
            }
            
            $this->peminjaman->user_id = $_SESSION['user_id'];
            $this->peminjaman->no_ktp = $_POST['no_ktp'];
            $this->peminjaman->no_telepon = $_POST['no_telepon'];
            $this->peminjaman->ps_id = $_POST['ps_id'];
            $this->peminjaman->tanggal_pinjam = $_POST['tanggal_pinjam'];
            $this->peminjaman->durasi_jam = $_POST['durasi_jam'];
            $this->peminjaman->total_harga = $_POST['total_harga'];
            $this->peminjaman->status = 'pending';

            try {
                if($this->peminjaman->create()) {
                    // Update status PS menjadi dipinjam
                    $this->ps->id = $_POST['ps_id'];
                    $this->ps->status = 'dipinjam';
                    $this->ps->updateStatus();
                    
                    // Log aktivitas
                    require_once 'controllers/ActivityLogController.php';
                    $ps_stmt = $this->ps->readOne();
                    $ps_data = $ps_stmt->fetch(PDO::FETCH_ASSOC);
                    ActivityLogController::log('create', 'Mengajukan peminjaman PS: ' . $ps_data['nama_ps'] . ' untuk ' . $_POST['durasi_jam'] . ' jam');
                    
                    $_SESSION['success'] = "Peminjaman berhasil diajukan!";
                } else {
                    $_SESSION['error'] = "Gagal mengajukan peminjaman!";
                }
            } catch(PDOException $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
            
            header('Location: index.php?page=peminjaman&action=index');
            exit();
        }
    }

    // Menampilkan form edit peminjaman dengan validasi hak akses.
    private function edit() {
        AuthController::checkRole(['admin', 'petugas', 'peminjam']);
        
        $this->peminjaman->id = $_GET['id'];
        $stmt = $this->peminjaman->readOne();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Peminjam hanya bisa edit peminjaman sendiri yang masih pending
        if($_SESSION['role'] == 'peminjam') {
            if($data['user_id'] != $_SESSION['user_id'] || $data['status'] != 'pending') {
                $_SESSION['error'] = "Anda tidak dapat mengedit peminjaman ini!";
                header('Location: index.php?page=peminjaman&action=index');
                exit();
            }
        }
        
        $ps_stmt = $this->ps->readAvailable();
        require_once 'views/peminjaman/edit.php';
    }

    // Memperbarui data peminjaman dari form edit.
    private function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->peminjaman->id = $_POST['id'];
            $this->peminjaman->ps_id = $_POST['ps_id'];
            $this->peminjaman->tanggal_pinjam = $_POST['tanggal_pinjam'];
            $this->peminjaman->durasi_jam = $_POST['durasi_jam'];
            $this->peminjaman->total_harga = $_POST['total_harga'];

            if($this->peminjaman->update()) {
                $_SESSION['success'] = "Peminjaman berhasil diupdate!";
            } else {
                $_SESSION['error'] = "Gagal mengupdate peminjaman!";
            }
            header('Location: index.php?page=peminjaman&action=index');
            exit();
        }
    }

    // Menyetujui pengajuan peminjaman.
    private function approve() {
        AuthController::checkRole(['admin', 'petugas']);
        
        $this->peminjaman->id = $_GET['id'];
        
        // Get peminjaman data untuk log
        $stmt = $this->peminjaman->readOne();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->peminjaman->status = 'disetujui';
        $this->peminjaman->approved_by = $_SESSION['user_id'];
        
        if($this->peminjaman->updateStatus()) {
            // Log aktivitas
            require_once 'controllers/ActivityLogController.php';
            ActivityLogController::log('approve', 'Menyetujui peminjaman ID #' . $_GET['id'] . ' oleh ' . $data['nama_peminjam']);
            
            $_SESSION['success'] = "Peminjaman berhasil disetujui oleh " . $_SESSION['nama'] . "!";
        } else {
            $_SESSION['error'] = "Gagal menyetujui peminjaman!";
        }
        header('Location: index.php?page=peminjaman&action=index');
        exit();
    }

    // Menolak pengajuan peminjaman dan mengembalikan status PS menjadi tersedia.
    private function reject() {
        AuthController::checkRole(['admin', 'petugas']);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->peminjaman->id = $_POST['id'];
            $stmt = $this->peminjaman->readOne();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->peminjaman->status = 'ditolak';
            $this->peminjaman->approved_by = $_SESSION['user_id'];
            $this->peminjaman->alasan_tolak = trim($_POST['alasan_tolak'] ?? '');

            if(empty($this->peminjaman->alasan_tolak)) {
                $_SESSION['error'] = "Alasan penolakan harus diisi!";
                header('Location: index.php?page=peminjaman&action=index');
                exit();
            }

            if($this->peminjaman->updateStatus()) {
                $this->ps->id = $data['ps_id'];
                $this->ps->status = 'tersedia';
                $this->ps->updateStatus();

                require_once 'controllers/ActivityLogController.php';
                ActivityLogController::log('reject', 'Menolak peminjaman ID #' . $_POST['id'] . ' - Alasan: ' . $this->peminjaman->alasan_tolak);

                $_SESSION['success'] = "Peminjaman berhasil ditolak!";
            } else {
                $_SESSION['error'] = "Gagal menolak peminjaman!";
            }
            header('Location: index.php?page=peminjaman&action=index');
            exit();
        }

        // GET: tampilkan form alasan
        $this->peminjaman->id = $_GET['id'];
        $stmt = $this->peminjaman->readOne();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        require_once 'views/peminjaman/reject.php';
    }

    // Mengarahkan proses selesai ke form pengembalian.
    private function selesai() {
        AuthController::checkRole(['admin', 'petugas']);
        
        // Redirect ke form pengembalian
        header('Location: index.php?page=peminjaman&action=pengembalian&id=' . $_GET['id']);
        exit();
    }
    
    // Menampilkan form pengembalian berdasarkan id peminjaman.
    private function pengembalian() {
        AuthController::checkRole(['admin', 'petugas']);
        
        $this->peminjaman->id = $_GET['id'];
        $stmt = $this->peminjaman->readOne();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        require_once 'views/peminjaman/pengembalian.php';
    }
    
    // Memproses pengembalian, menghitung denda, dan menyelesaikan transaksi.
    private function prosesPengembalian() {
        AuthController::checkRole(['admin', 'petugas']);
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->peminjaman->id = $_POST['id'];
            
            // Get peminjaman data
            $stmt = $this->peminjaman->readOne();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->peminjaman->kondisi_ps = $_POST['kondisi_ps'];
            $this->peminjaman->keterangan = $_POST['keterangan'] ?? '';
            
            // Hitung denda otomatis jika kondisi rusak
            // Denda = Rp 5.000 per jam
            if($_POST['kondisi_ps'] == 'rusak') {
                $denda_per_jam = 5000;
                $this->peminjaman->denda = $denda_per_jam * $data['durasi_jam'];
            } else {
                $this->peminjaman->denda = 0;
            }
            
            if($this->peminjaman->updatePengembalian()) {
                // Update status PS kembali tersedia
                $this->ps->id = $data['ps_id'];
                $this->ps->status = 'tersedia';
                $this->ps->updateStatus();
                
                // Log aktivitas
                require_once 'controllers/ActivityLogController.php';
                $denda_text = $this->peminjaman->denda > 0 ? ' dengan denda Rp ' . number_format($this->peminjaman->denda, 0, ',', '.') : ' tanpa denda';
                ActivityLogController::log('return', 'Memproses pengembalian PS ID #' . $_POST['id'] . ' kondisi ' . $_POST['kondisi_ps'] . $denda_text);
                
                if($this->peminjaman->denda > 0) {
                    $_SESSION['success'] = "Pengembalian berhasil diproses! Denda: Rp " . number_format($this->peminjaman->denda, 0, ',', '.');
                } else {
                    $_SESSION['success'] = "Pengembalian berhasil diproses tanpa denda!";
                }
            } else {
                $_SESSION['error'] = "Gagal memproses pengembalian!";
            }
            header('Location: index.php?page=peminjaman&action=index');
            exit();
        }
    }
}
