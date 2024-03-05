<div class="container-fluid">
  <!--Bagian heading -->
  <h1 class="h3 mb-2 text-gray-800">Produk</h1>
  <p class="mb-4">Halaman produk berisi informasi seluruh produk yang dapat di kelola oleh admin.</p>
  <a href="index.php?page=dashboard"><button class="btn btn-primary"><i class="fa fa-angle-left"></i> Halaman Dashboard</button></a>
  <br /><br />
  <?php
  //Validasi untuk menampilkan pesan pemberitahuan saat user menambah produk
  if (isset($_GET['add'])) {
    if ($_GET['add'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Produk telah ditambah!</div>";
    } else if ($_GET['add'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Produk gagal ditambahkan!</div>";
    }
  }
  //Validasi untuk menampilkan pesan pemberitahuan saat user mengubah produk
  if (isset($_GET['edit'])) {
    if ($_GET['edit'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Produk telah diupdate!</div>";
    } else if ($_GET['edit'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Produk gagal diupdate!</div>";
    }
  }
  //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus produk
  if (isset($_GET['hapus'])) {
    if ($_GET['hapus'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Produk telah dihapus!</div>";
    } else if ($_GET['hapus'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Produk gagal dihapus!</div>";
    }
  }

  ?>
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <!-- Tombol tambah produk -->
      <button class="btn-tambah btn btn-dark btn-icon-split" data-toggle="modal" data-target="#myModal"><span class="text">Tambah</span></button>
      &nbsp;&nbsp;
     
      <form action="" method="POST" class="d-none d-sm-inline-block form-inline my-2 my-md-0 mw-100 navbar-search ml-2">
        <div class="input-group">
          <input type="text" class="form-control bg-white border-1 small" name="tcari" placeholder="Masukkan kata kunci">
          <div class="input-group-append">
            <button class="btn btn-dark" type="submit" name="bcari">
              <i class="fas fa-search fa-sm"></i>
            </button>
            <button class="btn btn-danger" type="submit" name="breset">Reset</button>
          </div>
        </div>
      </form>

    </div>
    <div class="card-body">

      <!-- Tabel daftar produk -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Produk</th>
              <th>Satuan</th>
              <th>Kategori Produk</th>
              <th>Supplier</th>
              <th>Stok</th>
              <th>Harga Beli</th>
              <th>Harga Jual</th>
              <th width="13%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $time_start = microtime(true);
            $kon = mysqli_connect("localhost", "root", "", "kasir");

            // perintah sql untuk menampilkan daftar produk
            if (isset($_POST['bcari'])) {
              $cari = $_POST['tcari'];
              $sql = "SELECT * FROM produk p left join kategori_produk k on k.id_kt_produk=p.kategori_produk 
              left join supplier s on s.id_supplier=p.supplier 
              WHERE kode_produk LIKE '%" . $cari . "%' or nama_produk LIKE '%" . $cari . "%' 
              or satuan LIKE '%" . $cari . "%' or nama_kt_produk LIKE '%" . $cari . "%' 
              or nama_supplier LIKE '%" . $cari . "%' 
              or stok_produk LIKE '%" . $cari . "%' or harga_beli LIKE '%" . $cari . "%' 
              or harga_jual LIKE '%" . $cari . "%' order by id_produk desc";
              echo 'Total Excecution Time in seconds : ' . round((microtime(true) - $time_start), 3) . ' seconds';
            } else {
              $sql = "SELECT * FROM produk p left join kategori_produk k on k.id_kt_produk=p.kategori_produk 
              left join supplier s on s.id_supplier=p.supplier order by id_produk desc";
            }

            $hasil = mysqli_query($kon, $sql);
            $no = 0;
            //Menampilkan data dengan perulangan while
            while ($data = mysqli_fetch_array($hasil)) :
              $no++;
            ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $data['kode_produk']; ?></td>
                <td><?php echo $data['nama_produk']; ?></td>
                <td><?php echo $data['satuan']; ?></td>
                <td><?php echo $data['nama_kt_produk']; ?></td>
                <td><?php echo $data['nama_supplier']; ?></td>
                <td><?php echo $data['stok_produk']; ?></td>
                <td>Rp. <?php echo number_format($data['harga_beli'], 2, ',', '.'); ?></td>
                <td>Rp. <?php echo number_format($data['harga_jual'], 2, ',', '.'); ?></td>
                <td>
                  <button class="btn-gambar btn btn-info btn-circle" id_produk="<?php echo $data['id_produk']; ?>" kode_produk="<?php echo $data['kode_produk']; ?>" data-toggle="tooltip" title="Lihat gambar" data-placement="top"><i class="fas fa-image"></i></button>
                  <button class="btn-edit btn btn-warning btn-circle" id_produk="<?php echo $data['id_produk']; ?>" kode_produk="<?php echo $data['kode_produk']; ?>" data-toggle="tooltip" title="Edit Produk" data-placement="top"><i class="fas fa-edit"></i></button>
                  <button class="btn-hapus btn btn-danger btn-circle" id_produk="<?php echo $data['id_produk']; ?>" kode_produk="<?php echo $data['kode_produk']; ?>" gambar_produk="<?php echo $data['gambar_produk']; ?>" data-toggle="tooltip" title="Hapus Produk" data-placement="top"><i class="fas fa-trash"></i></button>
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

<div id="ModalCetakPDF" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Cetak Laporan PDF Data Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>

      <div class="modal-body" style="border-style: inset;">
        <form action="page/produk/cetak/cetak_produk_pdf.php" method="post" target="_blank">

          <div class="form-group">
            <p align="center" style="font-size: 20px;">[ bisa cetak salah satu saja ]</p>
          </div>

          <div class="form-group">
            <label>Kategori Produk</label>
            <?php
            include "config/database.php";

            echo "<select name='kt_produk' class='form-control'>";
            $tampil = mysqli_query($kon, "SELECT * FROM kategori_produk ORDER BY id_kt_produk asc");
            echo "<option value='' selected>- PILIH KATEGORI PRODUK-</option>";

            while ($w = mysqli_fetch_array($tampil)) {
              echo "<option>$w[nama_kt_produk]</option>";
            }
            echo "</select>";
            ?>
          </div>

          <div class="form-group">
            <p align="right"><input type="submit" name="cetak" class="btn btn-primary btn-sm" value="Cetak"></p>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <form action="page/produk/cetak/cetak_produk_pdf.php" method="post" target="_blank">
          <p align="right"><input type="submit" name="cetak_semua" class="btn btn-success btn-sm" value="Cetak Semua Data"></p>
        </form>
      </div>

    </div>
  </div>
</div>

<div id="ModalCetakEXCEL" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Cetak Laporan Excel Data Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>

      <div class="modal-body" style="border-style: inset;">
        <form action="page/produk/cetak/cetak_produk_excel.php" method="post" target="_blank">

          <div class="form-group">
            <p align="center" style="font-size: 20px;">[ bisa cetak salah satu saja ]</p>
          </div>

          <div class="form-group">
            <label>Kategori Produk</label>
            <?php
            include "config/database.php";

            echo "<select name='kt_produk' class='form-control'>";
            $tampil = mysqli_query($kon, "SELECT * FROM kategori_produk ORDER BY id_kt_produk asc");
            echo "<option value='' selected>- PILIH KATEGORI PRODUK-</option>";

            while ($w = mysqli_fetch_array($tampil)) {
              echo "<option>$w[nama_kt_produk]</option>";
            }
            echo "</select>";
            ?>
          </div>

          <div class="form-group">
            <p align="right"><input type="submit" name="cetak" class="btn btn-primary btn-sm" value="Cetak"></p>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <form action="page/produk/cetak/cetak_produk_excel.php" method="post" target="_blank">
          <p align="right"><input type="submit" name="cetak_semua" class="btn btn-success btn-sm" value="Cetak Semua Data"></p>
        </form>
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

    var id_produk = $(this).attr("id_produk");
    var kode_produk = $(this).attr("kode_produk");
    $.ajax({
      url: 'page/produk/gambar.php',
      method: 'post',
      data: {
        id_produk: id_produk
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Gambar Produk #' + kode_produk;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });

  // tambah produk
  $('.btn-tambah').on('click', function() {

    $.ajax({
      url: 'page/produk/tambah-produk.php',
      method: 'post',
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Tambah Produk';
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });


  // edit produk
  $('.btn-edit').on('click', function() {

    var id_produk = $(this).attr("id_produk");
    var kode_produk = $(this).attr("kode_produk");
    $.ajax({
      url: 'page/produk/edit-produk.php',
      method: 'post',
      data: {
        id_produk: id_produk
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Edit Produk #' + kode_produk;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });


  // hapus produk
  $('.btn-hapus').on('click', function() {

    var id_produk = $(this).attr("id_produk");
    var kode_produk = $(this).attr("kode_produk");
    var gambar_produk = $(this).attr("gambar_produk");
    $.ajax({
      url: 'page/produk/hapus-produk.php',
      method: 'post',
      data: {
        id_produk: id_produk,
        kode_produk: kode_produk,
        gambar_produk: gambar_produk
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