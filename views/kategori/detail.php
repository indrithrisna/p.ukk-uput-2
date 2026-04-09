<?php require_once 'views/layouts/header.php'; ?>

<div class="content">
    <h1>🗂️ <?php echo htmlspecialchars($dataKategori['nama_kategori']); ?></h1>

    <?php if(!empty($dataKategori['deskripsi'])): ?>
        <p style="color:white;margin-bottom:20px;opacity:0.9"><?php echo htmlspecialchars($dataKategori['deskripsi']); ?></p>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div style="margin-bottom:20px;display:flex;gap:10px">
        <a href="index.php?page=kategori&action=alat_create&kategori_id=<?php echo $dataKategori['id']; ?>" class="btn">+ Tambah Alat</a>
        <a href="index.php?page=kategori&action=index" class="btn btn-danger">← Kembali</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Jumlah</th>
                <th>Kondisi</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; $rows = $stmtAlat->fetchAll(PDO::FETCH_ASSOC); ?>
            <?php if(empty($rows)): ?>
            <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:30px">Belum ada alat. Klik "+ Tambah Alat" untuk menambahkan.</td></tr>
            <?php else: foreach($rows as $row): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['nama_alat']); ?></td>
                <td><?php echo $row['jumlah']; ?></td>
                <td>
                    <span class="badge badge-<?php echo $row['kondisi']; ?>">
                        <?php echo ucfirst($row['kondisi']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                <td>
                    <a href="index.php?page=kategori&action=alat_edit&id=<?php echo $row['id']; ?>&kategori_id=<?php echo $dataKategori['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="index.php?page=kategori&action=alat_delete&id=<?php echo $row['id']; ?>&kategori_id=<?php echo $dataKategori['id']; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus alat ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
