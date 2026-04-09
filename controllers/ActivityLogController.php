<?php
require_once 'models/ActivityLog.php';

class ActivityLogController {
    private $db;
    private $activityLog;

    // Inisialisasi koneksi database dan model activity log.
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->activityLog = new ActivityLog($this->db);
    }

    // Menampilkan daftar log aktivitas dengan filter opsional.
    public function index() {
        AuthController::checkRole(['admin', 'petugas']);
        
        $filters = [];
        
        if(isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $filters['user_id'] = $_GET['user_id'];
        }
        
        if(isset($_GET['action']) && !empty($_GET['action'])) {
            $filters['action'] = $_GET['action'];
        }
        
        if(isset($_GET['date_from']) && !empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        
        if(isset($_GET['date_to']) && !empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }
        
        $stmt = $this->activityLog->read($filters);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get all users for filter
        $user = new User($this->db);
        $users_stmt = $user->read();
        $users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/activity_log/index.php';
    }

    // Menampilkan statistik aktivitas berdasarkan rentang tanggal.
    public function stats() {
        AuthController::checkRole(['admin']);
        
        $date_from = $_GET['date_from'] ?? null;
        $date_to = $_GET['date_to'] ?? null;
        
        $stmt = $this->activityLog->getStats($date_from, $date_to);
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/activity_log/stats.php';
    }

    // Helper statis untuk mencatat log dari controller lain.
    public static function log($action, $description) {
        if(!isset($_SESSION['user_id'])) {
            return false;
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $activityLog = new ActivityLog($db);
        
        return $activityLog->log($_SESSION['user_id'], $action, $description);
    }
}
