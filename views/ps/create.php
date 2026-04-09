<?php require_once 'views/layouts/header.php'; ?>
<?php // Form tambah data PS baru. ?>

<div class="content">
    <h1>Tambah PS</h1>
    
    <div class="form-container">
        <form method="POST" action="index.php?page=ps&action=store">
            <div class="form-group">
                <label>Nama PS</label>
                <input type="text" name="nama_ps" placeholder="Contoh: PS 1" required>
            </div>
            
            <div class="form-group">
                <label>Tipe</label>
                <select name="tipe" required>
                    <option value="">Pilih Tipe</option>
                    <option value="PS3">PS3</option>
                    <option value="PS4">PS4</option>
                    <option value="PS5">PS5</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="dipinjam">Dipinjam</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Harga per Jam (Rp)</label>
                <input type="number" name="harga_per_jam" placeholder="10000" required>
            </div>
            
            <button type="submit" class="btn">Simpan</button>
            <a href="index.php?page=ps&action=index" class="btn btn-danger">Batal</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
