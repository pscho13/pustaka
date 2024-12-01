<?php include '../app/views/templates/header.php'; ?>
<div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <h3 class="profile-username text-center"><?= $data['buku']['Judul']; ?></h3>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Penulis</b> 
                    <label class="badge badge-info float-right"><?= $data['buku']['Penulis']; ?></label>
                  </li>
                  <li class="list-group-item">
                    <b>Penerbit</b> 
                    <label class="badge badge-info float-right"><?= $data['buku']['Penerbit']; ?></label>
                  </li>
                  <li class="list-group-item">
                    <b>TahunTerbit</b> 
                    <label class="badge badge-info float-right"><?= $data['buku']['TahunTerbit']; ?></label>
                  </li>
                  
                  <div class="d-flex flex-column">
                    <a href="<?= urlTo('/perpustakaan'); ?>"
                    class="btn btn-danger btn-block">
                      <b>Kembali</b>
                    </a>
                    <a href="<?= urlTo('/perpustakaan/'.$data['buku']['BukuID'].'/ulasanbuku'); ?>" 
                    class="btn btn-success btn-block">
                      <b>Berikan Ulasan</b>
                    </a>

                    <form action="<?= urlTo('/koleksi/store') ?>" method="post" class="">
                      <input type="hidden" name="BukuID" value="<?= $data['buku']['BukuID']; ?>">
                      <input type="hidden" name="UserID" value="<?= $_SESSION['UserID']; ?>">
                      <button class="btn btn-warning btn-block mt-2">
                        <b>Masukan ke koleksi pribadi</b>
                      </button>
                    </form>

                    <form action="<?= urlTo('/peminjaman/store') ?>" method="post" class="">
                      <input type="hidden" name="BukuID" value="<?= $data['buku']['BukuID']; ?>">
                      <button class="btn btn-primary btn-block mt-2">
                        <b>Pinjam</b>
                      </button>
                    </form>
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card card-primary card-outline">
            	<div class="card-header">
            		<h4>Ulasan</h4>
            	</div>

            	<div class="card-body">
            		<table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Ulasan</th>
                    <th>Rating</th>
                    <th>Pemberi Ulasan</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($data['ulasan'] as $ulasan): ?>
                    <tr>
                      <td><?= $ulasan['Ulasan']; ?></td>
                      <td><?= $ulasan['Rating']; ?></td>
                      <td><?= $ulasan['NamaLengkap']; ?></td>
                    </tr>
                  <?php endforeach ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Ulasan</th>
                    <th>Rating</th>
                    <th>Pemberi Ulasan</th>
                  </tr>
                  </tfoot>
                </table>
            	</div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
<?php include '../app/views/templates/footer.php'; ?>