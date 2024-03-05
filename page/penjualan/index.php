<div class="container-fluid">
  <!--Bagian heading -->
  <h1 class="h3 mb-2 text-gray-800">Penjualan</h1>
  <p class="mb-4">Halaman penjualan berisi informasi seluruh penjualan yang dapat di kelola oleh admin.</p>
  <a href="index.php?page=dashboard"><button class="btn btn-primary"><i class="fa fa-angle-left"></i> Halaman Dashboard</button></a>
  <br /><br />
  <?php
  //Validasi untuk menampilkan pesan pemberitahuan saat user menambah penjualan
  if (isset($_GET['add'])) {
    if ($_GET['add'] == 'berhasil') {
      echo "<div class='alert alert-success'>Transaksi Berhasil</div>";
    } else if ($_GET['add'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Data penjualan gagal ditambahkan!</div>";
    } else {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Pembayaran kurang!</div>";
    }
  }

  //Validasi untuk menampilkan pesan pemberitahuan saat user mengubah penjualan
  if (isset($_GET['edit'])) {
    if ($_GET['edit'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Data penjualan telah diupdate!</div>";
    } else if ($_GET['edit'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Data penjualan gagal diupdate!</div>";
    }
  }

  //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus penjualan
  if (isset($_GET['hapus'])) {
    if ($_GET['hapus'] == 'berhasil') {
      echo "<div class='alert alert-success'><strong>Berhasil!</strong> Data penjualan telah dihapus!</div>";
    } else if ($_GET['hapus'] == 'gagal') {
      echo "<div class='alert alert-danger'><strong>Gagal!</strong> Data penjualan gagal dihapus!</div>";
    }
  }
  ?>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
    </div>
    <div class="card-body">
      <!-- Tabel daftar penjualan -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>No Invoice</th>
              <th>Tanggal</th>
              <th>Kasir</th>
              <th>Pelanggan</th>
              <th>Total Bayar</th>
              <th>Bayar</th>
              <th>Kembali</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Koneksi database
            include 'config/database.php';
            $kasir = 0;
            if ($_SESSION["level"] == "Kasir") {
              $kasir = $_SESSION["id_pengguna"];
              $sql = "select * from penjualan left join pelanggan on penjualan.kode_pelanggan=pelanggan.kode_pelanggan left join pengguna on pengguna.id_pengguna=penjualan.id_kasir where id_kasir=$kasir order by id_penjualan desc";
            } else {
              $sql = "select * from penjualan left join pelanggan on penjualan.kode_pelanggan=pelanggan.kode_pelanggan left join pengguna on pengguna.id_pengguna=penjualan.id_kasir order by id_penjualan desc";
            }

            $hasil = mysqli_query($kon, $sql);
            $no = 0;
            //Menampilkan data dengan perulangan while
            while ($data = mysqli_fetch_array($hasil)) :
              $no++;
            ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $data['no_invoice']; ?></td>
                <td><?php echo date('d-m-Y', strtotime($data["tanggal"])); ?></td>
                <td><?php echo $data['nama_pengguna']; ?></td>
                <td><?php echo $data['nama_pelanggan']; ?></td>
                <td>Rp. <?php echo number_format($data['total_bayar'], 0, ',', '.'); ?></td>
                <td>Rp. <?php echo number_format($data['bayar'], 0, ',', '.'); ?></td>
                <td>Rp. <?php echo number_format($data['kembali'], 0, ',', '.'); ?></td>
                <td>
                  <a href="index.php?page=detail_penjualan&id_penjualan=<?php echo $data['id_penjualan']; ?>" class="btn btn-success btn-circle" id_penjualan="<?php echo $data['id_penjualan']; ?>" data-toggle="tooltip" title="Detail penjualan" data-placement="top"><i class="fas fa-mouse-pointer"></i></a>
                  <?php if ($_SESSION["level"] == "Admin") : ?>
                    <button class="btn-hapus btn btn-danger btn-circle" id_penjualan="<?php echo $data['id_penjualan']; ?>" no_invoice="<?php echo $data['no_invoice']; ?>" data-toggle="tooltip" title="Hapus penjualan" data-placement="top"><i class="fas fa-trash"></i></button>
                  <?php endif; ?>
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
          <!-- Data akan ditampilkan disini menggunakan AJAX -->
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


  // Tambah penjualan
  $('.btn-tambah').on('click', function() {

    $.ajax({
      url: 'page/penjualan/tambah-penjualan.php',
      method: 'post',
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Tambah penjualan';
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });



  // Hapus penjualan
  $('.btn-hapus').on('click', function() {

    var id_penjualan = $(this).attr("id_penjualan");
    var no_invoice = $(this).attr("no_invoice");

    $.ajax({
      url: 'page/penjualan/hapus-penjualan.php',
      method: 'post',
      data: {
        id_penjualan: id_penjualan,
        no_invoice: no_invoice
      },
      success: function(data) {
        $('#tampil_data').html(data);
        document.getElementById("judul").innerHTML = 'Hapus penjualan #';
      }
    });
    // Membuka modal
    $('#modal').modal('show');
  });
</script>