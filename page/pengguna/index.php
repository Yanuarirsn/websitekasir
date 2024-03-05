<?php
$pengguna = "";
$aksi = "disabled";
if (isset($_GET['pengguna'])) {
  switch ($_GET['pengguna']) {
    case 'Admin':
      $pengguna = "Admin";
      $aksi = "";
      break;
    case 'Kasir':
      $pengguna = "Kasir";
      $aksi = "";
      break;
    case 'Manajer':
      $pengguna = "Manajer";
      $aksi = "";
      break;
    default:
      echo "<center><h3>Pengguna tidak valid !</h3></center>";
      break;
  }
}

?>
<div class="container-fluid">
  <!--Bagian heading -->
  <h1 class="h3 mb-2 text-gray-800">Pengguna <?php echo $pengguna; ?></h1>
  <p class="mb-4">Halaman pengguna berisi informasi seluruh pengguna yang dapat di kelola oleh admin.</p>
  <a href="index.php?page=dashboard"><button class="btn btn-primary"><i class="fa fa-angle-left"></i> Halaman Dashboard</button></a>
  <br /><br />
  <?php
  //Validasi untuk menampilkan pesan pemberitahuan saat user menambah pengguna
  if (isset($_GET['add'])) {
    if ($_GET['add'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Pengguna telah ditambahkan!</div>";
    } else if ($_GET['add'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Pengguna gagal ditambahkan!</div>";
    }
  }

  //Validasi untuk menampilkan pesan pemberitahuan saat user mengubah pengguna
  if (isset($_GET['edit'])) {
    if ($_GET['edit'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Pengguna telah diupdate!</div>";
    } else if ($_GET['edit'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Pengguna gagal diupdate!</div>";
    }
  }
  //Validasi untuk menampilkan pesan pemberitahuan saat user hapus pengguna
  if (isset($_GET['hapus'])) {
    if ($_GET['hapus'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Pengguna telah dihapus!</div>";
    } else if ($_GET['hapus'] == 'berhasil') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Pengguna gagal dihapus!</div>";
    }
  }
  ?>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <!-- Tombol tambah pengguna -->
      <button <?php echo $aksi; ?> pengguna="<?php echo $pengguna; ?>" class="btn-tambah btn btn-dark btn-icon-split"><span class="text">Tambah</span></button>
    </div>
    <div class="card-body">
      <!-- Tabel daftar pengguna -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th width="10%">Foto</th>
              <th>Nama</th>
              <th>Email</th>
              <th>No Telp</th>
              <th>Level</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            //Koneksi database
            include 'config/database.php';
            //perintah sql untuk menampilkan daftar pengguna
            $sql = "select * from pengguna where level='$pengguna' order by id_pengguna desc";
            $hasil = mysqli_query($kon, $sql);
            $no = 0;
            //Menampilkan data dengan perulangan while
            while ($data = mysqli_fetch_array($hasil)) :
              $no++;
            ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $data['kode_pengguna']; ?></td>
                <td> <img src="page/pengguna/foto/<?php echo $data['foto']; ?>" width="90%" class="img-thumbnail"></td>
                <td><?php echo $data['nama_pengguna']; ?></td>
                <td><?php echo $data['email']; ?></td>
                <td><?php echo $data['no_telp']; ?></td>
                <td><?php echo $data['level']; ?></td>
                <td><?php echo $data['status'] == 1 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                <td>
                  <button class="btn-edit btn btn-warning btn-circle" id_pengguna="<?php echo $data['id_pengguna']; ?>" kode_pengguna="<?php echo $data['kode_pengguna']; ?>" data-toggle="tooltip" title="Edit pengguna" data-placement="top"><i class="fas fa-edit"></i></button>
                  <button class="btn-hapus btn btn-danger btn-circle" id_pengguna="<?php echo $data['id_pengguna']; ?>" kode_pengguna="<?php echo $data['kode_pengguna']; ?>" level="<?php echo $data['level']; ?>" foto="<?php echo $data['foto']; ?>" data-toggle="tooltip" title="Hapus pengguna" data-placement="top"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
              <!-- bagian akhir (penutup) while -->
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Bagian header -->
      <div class="modal-header">
        <h4 class="modal-title" id="judul"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Bagian body -->
      <div class="modal-body">

        <div id="tampil_data">
          <!-- Data akan ditampilkan disini dengan AJAX -->
        </div>

      </div>
      <!-- Bagian footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
  // untuk tooltip (bootstrap)
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });

  //Tambah pengguna
  $('.btn-tambah').on('click', function() {
    var pengguna = $(this).attr("pengguna");
    $.ajax({
      url: 'page/pengguna/tambah-pengguna.php',
      method: 'post',
      data: {
        pengguna: pengguna
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Tambah Pengguna ' + pengguna;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });


  //Edit pengguna
  $('.btn-edit').on('click', function() {

    var id_pengguna = $(this).attr("id_pengguna");
    var kode_pengguna = $(this).attr("kode_pengguna");
    $.ajax({
      url: 'page/pengguna/edit-pengguna.php',
      method: 'post',
      data: {
        id_pengguna: id_pengguna
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Edit Pengguna #' + kode_pengguna;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });


  //Hapus pengguna
  $('.btn-hapus').on('click', function() {
    var kode_pengguna = $(this).attr("kode_pengguna");
    var id_pengguna = $(this).attr("id_pengguna");
    var level = $(this).attr("level");
    var foto = $(this).attr("foto");

    $.ajax({
      url: 'page/pengguna/hapus-pengguna.php',
      method: 'post',
      data: {
        id_pengguna: id_pengguna,
        kode_pengguna: kode_pengguna,
        level: level,
        foto: foto
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Hapus Pengguna #' + kode_pengguna;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });
</script>