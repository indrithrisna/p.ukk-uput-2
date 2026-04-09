<?php require_once 'views/layouts/header.php'; ?>

<div class="content">
    <h1>Tambah Alat — <?php echo htmlspecialchars($dataKategori['nama_kategori']); ?></h1>
    <div class="form-container">
        <form method="POST" action="index.php?page=kategori&action=alat_store">
            <input type="hidden" name="kategori_id" value="<?php echo $dataKategori['id']; ?>">
            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" placeholder="Contoh: Stik PS5" required>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" required>
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="perbaikan">Perbaikan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
            </div>
            <button type="submit" class="btn">Simpan</button>
            <a href="index.php?page=kategori&action=detail&id=<?php echo $dataKategori['id']; ?>" class="btn btn-danger">Batal</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
