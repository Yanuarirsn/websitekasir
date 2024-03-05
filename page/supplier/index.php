<div class="container-fluid">
  <!--Bagian heading -->
  <h1 class="h3 mb-2 text-gray-800">Supplier</h1>
  <p class="mb-4">Halaman supplier berisi informasi seluruh supplier yang dapat di kelola oleh admin.</p>
  <a href="index.php?page=dashboard"><button class="btn btn-primary"><i class="fa fa-angle-left"></i> Halaman Dashboard</button></a>
  <br /><br />
  <?php
  //Validasi untuk menampilkan pesan pemberitahuan saat user menambah supplier
  if (isset($_GET['add'])) {
    if ($_GET['add'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Supplier telah ditambah!</div>";
    } else if ($_GET['add'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Supplier gagal ditambahkan!</div>";
    }
  }
  //Validasi untuk menampilkan pesan pemberitahuan saat user mengubah supplier
  if (isset($_GET['edit'])) {
    if ($_GET['edit'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> supplier telah diupdate!</div>";
    } else if ($_GET['edit'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> supplier gagal diupdate!</div>";
    }
  }
  //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus supplier
  if (isset($_GET['hapus'])) {
    if ($_GET['hapus'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> supplier telah dihapus!</div>";
    } else if ($_GET['hapus'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> supplier gagal dihapus!</div>";
    }
  }
  ?>
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <!-- Tombol tambah supplier -->
      <button class="btn-tambah btn btn-dark btn-icon-split"><span class="text">Tambah</span></button>

    </div>
    <div class="card-body">
      <!-- Tabel daftar supplier -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>supplier</th>
              <th>Telp</th>
              <th>Alamat</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            //Koneksi database
            include 'config/database.php';
            // perintah sql untuk menampilkan daftar supplier
            $sql = "SELECT * FROM supplier order by id_supplier desc";
            $hasil = mysqli_query($kon, $sql);
            $no = 0;
            //Menampilkan data dengan perulangan while
            while ($data = mysqli_fetch_array($hasil)) :
              $no++;
            ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $data['kode_supplier']; ?></td>
                <td><?php echo $data['nama_supplier']; ?></td>
                <td><?php echo $data['no_telp']; ?></td>
                <td><?php echo $data['alamat_supplier']; ?></td>
                <td><?php echo $data['status'] == 1 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                <td>
                  <button class="btn-edit btn btn-warning btn-circle" id_supplier="<?php echo $data['id_supplier']; ?>" kode_supplier="<?php echo $data['kode_supplier']; ?>" data-toggle="tooltip" title="Edit supplier" data-placement="top"><i class="fas fa-edit"></i></button>
                  <button class="btn-hapus btn btn-danger btn-circle" id_supplier="<?php echo $data['id_supplier']; ?>" kode_supplier="<?php echo $data['kode_supplier']; ?>" data-toggle="tooltip" title="Hapus supplier" data-placement="top"><i class="fas fa-trash"></i></button>
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


  // Tambah supplier dengan AJAX
  $('.btn-tambah').on('click', function() {

    $.ajax({
      url: 'page/supplier/tambah-supplier.php',
      method: 'post',
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Tambah supplier';
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });


  // Edit supplier dengan AJAX
  $('.btn-edit').on('click', function() {

    var id_supplier = $(this).attr("id_supplier");
    var kode_supplier = $(this).attr("kode_supplier");
    $.ajax({
      url: 'page/supplier/edit-supplier.php',
      method: 'post',
      data: {
        id_supplier: id_supplier
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Edit supplier #' + kode_supplier;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });


  //Hapus supplier dengan AJAX
  $('.btn-hapus').on('click', function() {

    var id_supplier = $(this).attr("id_supplier");
    var kode_supplier = $(this).attr("kode_supplier");
    $.ajax({
      url: 'page/supplier/hapus-supplier.php',
      method: 'post',
      data: {
        id_supplier: id_supplier,
        kode_supplier: kode_supplier
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Hapus supplier #' + kode_supplier;
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });
</script>