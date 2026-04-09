<?php require_once 'views/layouts/header.php'; ?>

<div class="content">
    <h1>Edit Alat — <?php echo htmlspecialchars($dataAlat['nama_kategori']); ?></h1>
    <div class="form-container">
        <form method="POST" action="index.php?page=kategori&action=alat_update">
            <input type="hidden" name="id" value="<?php echo $dataAlat['id']; ?>">
            <input type="hidden" name="kategori_id" value="<?php echo $dataAlat['kategori_id']; ?>">
            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" value="<?php echo htmlspecialchars($dataAlat['nama_alat']); ?>" required>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="<?php echo $dataAlat['jumlah']; ?>" min="1" required>
            </div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" required>
                    <option value="baik"      <?php echo $dataAlat['kondisi']=='baik'      ? 'selected':''; ?>>Baik</option>
                    <option value="rusak"     <?php echo $dataAlat['kondisi']=='rusak'     ? 'selected':''; ?>>Rusak</option>
                    <option value="perbaikan" <?php echo $dataAlat['kondisi']=='perbaikan' ? 'selected':''; ?>>Perbaikan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="3"><?php echo htmlspecialchars($dataAlat['keterangan'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn">Update</button>
            <a href="index.php?page=kategori&action=detail&id=<?php echo $dataAlat['kategori_id']; ?>" class="btn btn-danger">Batal</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
