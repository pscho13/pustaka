<?php include '../app/views/templates/header.php'; ?>
<div class="col-md-6">
  <div class="card card-primary">
    <div class="card-body">
      <form action="<?= urlTo('/buku/store'); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="KategoriID">Kategori</label>
          <select id="KategoriID" name="KategoriID" class="form-control custom-select">
          <?php foreach ($data as $k): ?>
          <option value="<?= $k['KategoriID']; ?>"><?= $k['NamaKategori']; ?></option>
          <?php endforeach ?>
          </select>
        </div>

        <div class="form-group">
          <label for="Judul">Judul</label>
          <input type="text" id="Judul" name="Judul" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="Penulis">Penulis</label>
          <input type="text" id="Penulis" name="Penulis" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="Penerbit">Penerbit</label>
          <input type="text" id="Penerbit" name="Penerbit" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="TahunTerbit">TahunTerbit</label>
          <input type="number" id="TahunTerbit" name="TahunTerbit" class="form-control" required>
        </div>

                <!-- Tambahkan input untuk cover buku -->
        <div class="form-group">
          <label for="Cover">Cover Buku</label>
          <input type="file" id="Cover" name="Cover" class="form-control" accept="image/*" required>
        </div>

        <input type="hidden" id="updated_by" name="updated_by" class="form-control" value="<?= $_SESSION['username']; ?>" required>
      
        

        <div class="form-group">
          <a href="<?= urlTo('/buku'); ?>" class="btn btn-danger">Batal</a>
          <button type="submit" class="btn btn-primary float-right">Tambah Data</button>
        </div>
      </form>
    </div>
  </div>
</div>
<img src="" alt="">
<?php include '../app/views/templates/footer.php'; ?>