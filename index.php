<?php 
  require 'connection.php';
  checkLogin();

  $jml_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id_user) as jml_user FROM user"));
  $jml_user = $jml_user['jml_user'];

  $jml_jabatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id_jabatan) as jml_jabatan FROM jabatan"));
  $jml_jabatan = $jml_jabatan['jml_jabatan'];

  $jml_pemasukan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(jumlah_pemasukan) as jml_pemasukan FROM pemasukan"));
  $jml_pemasukan = $jml_pemasukan['jml_pemasukan'];

  $jml_pengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(jumlah_pengeluaran) as jml_pengeluaran FROM pengeluaran"));
  $jml_pengeluaran = $jml_pengeluaran['jml_pengeluaran'];
?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'include/css.php'; ?>
  <title>Dashboard</title>
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
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <?php if ($_SESSION['id_jabatan'] == '1'): ?>
            <div class="col-lg-4">
              <div class="card shadow">
                <div class="card-body">
                  <h5><i class="fas fa-fw fa-cog"></i> Jabatan</h5>
                  <h6 class="text-muted">Jumlah Jabatan: <?= $jml_jabatan; ?></h6>
                  <a href="jabatan.php" class="btn btn-info"><i class="fas fa-fw fa-align-justify"></i></a>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card shadow">
                <div class="card-body">
                  <h5><i class="fas fa-fw fa-users"></i> User</h5>
                  <h6 class="text-muted">Jumlah User: <?= $jml_user; ?></h6>
                  <a href="user.php" class="btn btn-info"><i class="fas fa-fw fa-align-justify"></i></a>
                </div>
              </div>
            </div>
          <?php endif ?>
          <div class="col-lg-4">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="text-success fas fa-fw fa-money-bill"></i> <i class="text-success fas fa-fw fa-dollar-sign"></i> Total Keuangan</h5>
                <h6 class="text-muted">Jumlah Pemasukan Uang: Rp. <?= number_format($jml_pemasukan - $jml_pengeluaran); ?></h6>
                <a href="uang_kas.php" class="btn btn-info"><i class="fas fa-fw fa-align-justify"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="text-success fas fa-fw fa-caret-up"></i> <i class="text-success fas fa-fw fa-dollar-sign"></i> Total Pemasukan Uang</h5>
                <h6 class="text-muted">Jumlah Pemasukan Uang: Rp. <?= number_format($jml_pemasukan); ?></h6>
                <a href="uang_kas.php" class="btn btn-info"><i class="fas fa-fw fa-align-justify"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="text-danger fas fa-fw fa-caret-down"></i><i class="text-danger fas fa-fw fa-dollar-sign"></i> Total Pengeluaran Uang</h5>
                <h6 class="text-muted">Jumlah Pengeluaran Uang: Rp. <?= number_format($jml_pengeluaran); ?></h6>
                <a href="pengeluaran.php" class="btn btn-info"><i class="fas fa-fw fa-align-justify"></i></a>
              </div>
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
