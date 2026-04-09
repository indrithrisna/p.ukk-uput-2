<?php require_once 'views/layouts/header.php'; ?>

<div class="content">
    <h1>📊 Log Aktivitas Sistem</h1>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #3b82f6;">
            <strong>ℹ️ Informasi:</strong> Log aktivitas tercatat <strong>OTOMATIS</strong> oleh sistem. Anda hanya perlu melihat dan memfilter data.
        </div>
                    <!-- Info Box -->
                    <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #3b82f6;">
                        <strong>ℹ️ Informasi:</strong> Halaman ini hanya untuk <strong>MELIHAT</strong> log aktivitas yang tercatat <strong>OTOMATIS</strong> oleh sistem. 
                        Anda tidak perlu menambahkan log secara manual. Log akan tercatat otomatis saat user melakukan aktivitas seperti login, tambah data, edit data, dll.
                    </div>
                    
        <!-- Filter Form -->
        <form method="GET" action="index.php" style="margin-bottom: 20px;">
            <input type="hidden" name="page" value="activity_log">
            
            <div class="form-group">
                <label>User</label>
                <select name="user_id">
                    <option value="">Semua User</option>
                    <?php foreach($users as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $user['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($user['nama']) ?> (<?= $user['role'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Aksi</label>
                <select name="action">
                    <option value="">Semua Aksi</option>
                    <option value="login" <?= (isset($_GET['action']) && $_GET['action'] == 'login') ? 'selected' : '' ?>>Login</option>
                    <option value="logout" <?= (isset($_GET['action']) && $_GET['action'] == 'logout') ? 'selected' : '' ?>>Logout</option>
                    <option value="create" <?= (isset($_GET['action']) && $_GET['action'] == 'create') ? 'selected' : '' ?>>Create</option>
                    <option value="update" <?= (isset($_GET['action']) && $_GET['action'] == 'update') ? 'selected' : '' ?>>Update</option>
                    <option value="delete" <?= (isset($_GET['action']) && $_GET['action'] == 'delete') ? 'selected' : '' ?>>Delete</option>
                    <option value="approve" <?= (isset($_GET['action']) && $_GET['action'] == 'approve') ? 'selected' : '' ?>>Approve</option>
                    <option value="reject" <?= (isset($_GET['action']) && $_GET['action'] == 'reject') ? 'selected' : '' ?>>Reject</option>
                    <option value="return" <?= (isset($_GET['action']) && $_GET['action'] == 'return') ? 'selected' : '' ?>>Return</option>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" value="<?= $_GET['date_from'] ?? '' ?>">
                </div>
                
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" value="<?= $_GET['date_to'] ?? '' ?>">
                </div>
            </div>
            
            <button type="submit" class="btn">🔍 Filter</button>
            <a href="index.php?page=activity_log" class="btn" style="background: linear-gradient(135deg, #64748b, #94a3b8);">🔄 Reset</a>
            
            <?php if($_SESSION['role'] == 'admin'): ?>
                <a href="index.php?page=activity_log&action=stats" class="btn" style="background: linear-gradient(135deg, #3b82f6, #60a5fa); margin-left: 10px;">
                    📊 Lihat Statistik
                </a>
            <?php endif; ?>
        </form>

        <!-- Table -->
        <div style="overflow-x: auto; margin-top: 20px;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 18%;">Waktu</th>
                        <th style="width: 20%;">User</th>
                        <th style="width: 12%;">Aksi</th>
                        <th style="width: 45%;">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($logs) > 0): ?>
                        <?php $no = 1; foreach($logs as $log): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($log['nama']) ?></strong><br>
                                <small style="color: #64748b;"><?= htmlspecialchars($log['username']) ?></small>
                            </td>
                            <td>
                                <?php
                                $badge_class = 'badge-pending';
                                $badge_text = strtoupper($log['action']);
                                switch($log['action']) {
                                    case 'login': $badge_class = 'badge-disetujui'; break;
                                    case 'logout': $badge_class = 'badge-pending'; break;
                                    case 'create': $badge_class = 'badge-tersedia'; break;
                                    case 'update': $badge_class = 'badge-dipinjam'; break;
                                    case 'delete': $badge_class = 'badge-ditolak'; break;
                                    case 'approve': $badge_class = 'badge-disetujui'; break;
                                    case 'reject': $badge_class = 'badge-ditolak'; break;
                                    case 'return': $badge_class = 'badge-selesai'; break;
                                }
                                ?>
                                <span class="badge <?= $badge_class ?>">
                                    <?= $badge_text ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($log['description']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #64748b;">
                                Tidak ada data log aktivitas
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
