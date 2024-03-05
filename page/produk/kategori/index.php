<div class="container-fluid">
  <!--Bagian heading -->
  <h1 class="h3 mb-2 text-gray-800">Kategori Produk</h1>
  <p class="mb-4">Kelolah data kategori produk di halaman ini.</p>
  <a href="index.php?page=dashboard"><button class="btn btn-primary"><i class="fa fa-angle-left"></i> Halaman Dashboard</button></a>
  <br /><br />
  <?php
  //Validasi untuk menampilkan pesan pemberitahuan saat user menambah kategori produk
  if (isset($_GET['add'])) {
    if ($_GET['add'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Produk telah ditambah!</div>";
    } else if ($_GET['add'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Produk gagal ditambahkan!</div>";
    }
  }
  //Validasi untuk menampilkan pesan pemberitahuan saat user mengubah kategori produk
  if (isset($_GET['edit'])) {
    if ($_GET['edit'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Kategori produk telah diupdate!</div>";
    } else if ($_GET['edit'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Kategori produk gagal diupdate!</div>";
    }
  }
  //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus kategori produk
  if (isset($_GET['hapus'])) {
    if ($_GET['hapus'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Kategori produk telah dihapus!</div>";
    } else if ($_GET['hapus'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Kategori produk gagal dihapus!</div>";
    }
  }
  ?>
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <!-- Tombol tambah kategori produk -->
      <button class="btn-tambah btn btn-dark btn-icon-split"><span class="text">Tambah</span></button>
    </div>
    <div class="card-body">

      <!-- Tabel daftar kategori produk -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Kategori Produk</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            //Koneksi database
            include 'config/database.php';
            // perintah sql untuk menampilkan kategori produk
            $sql = "select * from kategori_produk order by id_kt_produk desc";
            $hasil = mysqli_query($kon, $sql);
            $no = 0;
            //Menampilkan data dengan perulangan while
            while ($data = mysqli_fetch_array($hasil)) :
              $no++;
            ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $data['nama_kt_produk']; ?></td>
                <td>
                  <button class="btn-edit btn btn-warning btn-circle" id_kt_produk="<?php echo $data['id_kt_produk']; ?>" data-toggle="tooltip" title="Edit Produk" data-placement="top"><i class="fas fa-edit"></i></button>
                  <button class="btn-hapus btn btn-danger btn-circle" id_kt_produk="<?php echo $data['id_kt_produk']; ?>" data-toggle="tooltip" title="Hapus Produk" data-placement="top"><i class="fas fa-trash"></i></button>
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


<!-- Include modal tambah,edit dan hapus produk -->
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


  // lihat gambar produk
  $('.btn-gambar').on('click', function() {

    var id_kt_produk = $(this).attr("id_kt_produk");
    var kode_produk = $(this).attr("kode_produk");
    $.ajax({
      url: 'page/produk/gambar.php',
      method: 'post',
      data: {
        id_kt_produk: id_kt_produk
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Gambar Produk #' + kode_produk;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });

  //tambah kategori produk
  $('.btn-tambah').on('click', function() {

    var id_kt_produk = $(this).attr("id_kt_produk");

    $.ajax({
      url: 'page/produk/kategori/tambah-kategori.php',
      method: 'post',
      data: {
        id_kt_produk: id_kt_produk
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Tambah Kategori Produk #' + id_kt_produk;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });

  //edit kategori produk
  $('.btn-edit').on('click', function() {

    var id_kt_produk = $(this).attr("id_kt_produk");

    $.ajax({
      url: 'page/produk/kategori/edit-kategori.php',
      method: 'post',
      data: {
        id_kt_produk: id_kt_produk
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Edit Kategori Produk #' + id_kt_produk;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });


  //hapus kategori produk
  $('.btn-hapus').on('click', function() {

    var id_kt_produk = $(this).attr("id_kt_produk");

    $.ajax({
      url: 'page/produk/kategori/hapus-kategori.php',
      method: 'post',
      data: {
        id_kt_produk: id_kt_produk
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Hapus Produk #' + kode_produk;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });
</script>