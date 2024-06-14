<?php 
  require 'connection.php';
  checkLogin();
  $pemasukan = mysqli_query($conn, "SELECT * FROM pemasukan INNER JOIN user ON pemasukan.id_user = user.id_user");

  if (isset($_POST['btnAddPemasukan'])) {
    if (addPemasukan($_POST) > 0) {
      setAlert("Pemasukan Berhasil Ditambahkan", "Berhasil Menambahkan Data", "success");
      header("Location: pemasukan.php");
    }
  }

  if (isset($_POST['btnEditPemasukan'])) {
    if (editPemasukan($_POST) > 0) {
      setAlert("Pemasukan Berhasil Diubah", "Berhasil Mengubah Data", "success");
      header("Location: pemasukan.php");
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'include/css.php'; ?>
  <title>Pemasukan</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php include 'include/navbar.php'; ?>

  <?php include 'include/sidebar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm">
            <h1 class="m-0 text-dark">Pemasukan</h1>
          </div><!-- /.col -->
          <div class="col-sm text-right">
            <?php if ($_SESSION['id_jabatan'] !== '3'): ?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#tambahPemasukanModal"><i class="fas fa-fw fa-plus"></i> Tambah Pemasukan</button>
              <!-- Modal -->
              <div class="modal fade text-left" id="tambahPemasukanModal" tabindex="-1" role="dialog" aria-labelledby="tambahPemasukanModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <form method="post">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="tambahPemasukanModalLabel">Tambah Pemasukan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label for="jumlah_pemasukan">Jumlah Pemasukan</label>
                          <input type="number" name="jumlah_pemasukan" id="jumlah_pemasukan" required class="form-control" placeholder="Rp.">
                        </div>
                        <div class="form-group">
                          <label for="keterangan">Keterangan</label>
                          <textarea name="keterangan" id="keterangan" required class="form-control"></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Batal</button>
                        <button type="submit" name="btnAddPemasukan" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Tambah</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <?php endif ?>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg">
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped" id="table_id">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Username</th>
                    <th>Keterangan</th>
                    <th>Jumlah Pemasukan</th>
                    <?php if ($_SESSION['id_jabatan'] !== '3'): ?>
                      <th>Aksi</th>
                    <?php endif ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($pemasukan as $dp): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $dp['username']; ?></td>
                      <td><?= $dp['keterangan']; ?></td>
                      <td>Rp. <?= number_format($dp['jumlah_pemasukan']); ?></td>
                      <?php if ($_SESSION['id_jabatan'] !== '3'): ?>
                        <td>
                          <a href="" class="badge badge-success" data-toggle="modal" data-target="#editPemasukanModal<?= $dp['id_pemasukan']; ?>"><i class="fas fa-fw fa-edit"></i> Ubah</a>
                          <div class="modal fade text-left" id="editPemasukanModal<?= $dp['id_pemasukan']; ?>" tabindex="-1" role="dialog" aria-labelledby="editPemasukanModalLabel<?= $dp['id_pemasukan']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form method="post">
                                <input type="hidden" name="id_pemasukan" value="<?= $dp['id_pemasukan']; ?>">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="editPemasukanModalLabel<?= $dp['id_pemasukan']; ?>">Ubah Pemasukan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <label for="jumlah_pemasukan<?= $dp['id_pemasukan']; ?>">Jumlah Pemasukan</label>
                                      <input type="number" name="jumlah_pemasukan" id="jumlah_pemasukan<?= $dp['id_pemasukan']; ?>" required class="form-control" placeholder="Rp." value="<?= $dp['jumlah_pemasukan']; ?>">
                                    </div>
                                    <div class="form-group">
                                      <label for="keterangan<?= $dp['id_pemasukan']; ?>">Keterangan</label>
                                      <textarea name="keterangan" id="keterangan<?= $dp['id_pemasukan']; ?>" required class="form-control"><?= $dp['keterangan']; ?></textarea>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Batal</button>
                                    <button type="submit" name="btnEditPemasukan" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Ubah</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                          <?php if ($_SESSION['id_jabatan'] !== '3'): ?>
                            <a href="hapus_pemasukan.php?id_pemasukan=<?= $dp['id_pemasukan']; ?>" class="badge badge-danger btn-delete" data-nama="Pemasukan : Rp. <?= number_format($dp['jumlah_pemasukan']); ?> | <?= $dp['keterangan']; ?>"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                          <?php endif ?>
                        </td>
                      <?php endif ?>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong> &copy; UAS PEMROGRAMAN WEBSITE KELOMPOK 5.</strong>
  </footer>

</div>
</body>
</html>
