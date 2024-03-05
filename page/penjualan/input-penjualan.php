<div class="container-fluid">
  <!--Bagian heading -->
  <h1 class="h3 mb-2 text-gray-800">Input Penjualan</h1>

  <?php
  // mengambil data penjualan dengan kode paling besar
  include 'config/database.php';
  $query = mysqli_query($kon, "SELECT max(id_penjualan) as id_penjualan_terbesar FROM penjualan");
  $data = mysqli_fetch_array($query);
  $id_penjualan = $data['id_penjualan_terbesar'];
  $id_penjualan++;
  $huruf = "INV";
  $invoice = $huruf . sprintf("%04s", $id_penjualan);

  ?>

  <!--form -->
  <form action="page/penjualan/simpan-penjualan.php" method="post">
    <input type="hidden" name="no_invoice" value="<?php echo $invoice; ?>" />
    <input type="hidden" name="kode_pelanggan" value="1" id="kode_pelanggan" />
    <input type="hidden" name="id_kasir" value="<?php echo $_SESSION["id_pengguna"]; ?>" id="id_kasir" />

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <div class="row">
          <div class="col-sm-6">
            <h5 style="text-align:left">No Invoice : <?php echo $invoice; ?></h5>
          </div>
          <div class="col-sm-6">
            <h5 style="text-align:right">Tanggal : <?php echo date("d/m/Y"); ?></h5>
          </div>

        </div>
      </div>

      <div class="card-body">
        <!-- rows -->
        <div class="row">
          <div class="col-sm-4">
            <!-- Overflow Hidden -->
            <div class="card mb-4">
              <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Pembelian</h5>
              </div>
              <div class="card-body">
                <!-- rows -->
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <button type="button" data-toggle="modal" data-target="#modal" class="btn btn-primary"><span class="text"><i class="fas fa-shopping-bag fa-sm"></i> Pilih Produk</span></button>
                    </div>
                  </div>
                </div>
                <!-- rows -->
                <div class="row">
                  <div class="col-sm-12">
                    <div id="tampil_deskripsi_produk">
                    </div>
                    <div class="form-group">
                      <label>Produk:</label>
                      <input name="kode_produk" id="kode_produk" type="hidden" class="form-control">
                      <input name="nama_produk" id="nama_produk" type="text" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                      <label>Harga:</label>
                      <input name="harga" id="harga_produk" type="text" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                      <label>Stok Tersedia : <span id="info_stok_produk"> </span> </label>
                      <input type="hidden" id="stok_produk" name="stok_produk" />
                      <input name="jumlah" type="text" id="jumlah_beli" class="form-control" placeholder="Jumlah yang dibeli">
                    </div>
                    <div class="form-group" id="info_ketersediaan">
                    </div>
                    <div class="form-group">
                      <button type="button" id="tambahkan_ke_keranjang" aksi="tambahkan_ke_keranjang" disabled class="btn btn-primary btn-block"><span class="text"><i class="fas fa-shopping-cart fa-sm"></i> Masukan keranjang</span></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-8">
            <!-- Overflow Hidden -->
            <div class="card mb-4">
              <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Keranjang Belanja</h5>
                <p id="tampil_pelanggan">Pelanggan : - <a href="" data-toggle="modal" data-target="#pilih_pelanggan">Pilih</a></p>
              </div>
              <div class="card-body">
                <!--Menampilkan cart (keranjang belanja) -->
                <div id="tampil_cart"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- rows -->
      </div>
    </div>
    <!--form -->
  </form>
</div>


<!-- Modal -->
<div class="modal fade" id="modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <!-- Bagian header -->
      <div class="modal-header">
        <h4 class="modal-title" id="judul"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Bagian body -->
      <div class="modal-body">
        <!-- Tabel daftar produk -->
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Produk</th>
                <th>Satuan</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga Jual</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // include database
              include 'config/database.php';
              // perintah sql untuk menampilkan daftar produk yang berelasi dengan tabel kategori produk
              $sql = "select * from produk p left join kategori_produk k on k.id_kt_produk=p.kategori_produk where stok_produk>=1 order by id_produk desc";
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
                  <td><?php echo $data['stok_produk']; ?></td>
                  <td>Rp. <?php echo number_format($data['harga_jual'], 2, ',', '.'); ?></td>
                  <td>
                    <button type="button" class="btn-pilih-produk btn btn-primary btn-block" kode_produk="<?php echo $data['kode_produk']; ?>" data-dismiss="modal"><span class="text"><i class="fas fa-paper-plane fa-sm"></i> Pilih</span></button>
                  </td>
                </tr>
                <!-- bagian akhir (penutup) while -->
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Bagian footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="pilih_pelanggan">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Pilih Pelanggan</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <!-- Tabel daftar pelanggan -->
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>No Telp</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
                <th width="15%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // include database
              include 'config/database.php';
              // perintah sql untuk menampilkan daftar produk yang berelasi dengan tabel kategori produk
              $sql = "select * from pelanggan order by id_pelanggan desc";
              $hasil = mysqli_query($kon, $sql);
              $no = 0;
              //Menampilkan data dengan perulangan while
              while ($data = mysqli_fetch_array($hasil)) :
                $no++;
              ?>
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $data['kode_pelanggan']; ?></td>
                  <td><?php echo $data['nama_pelanggan']; ?></td>
                  <td><?php echo $data['no_telp']; ?></td>
                  <td><?php echo $data['alamat_pelanggan']; ?></td>
                  <td><?php echo $data['jenis_kelamin'] == 1 ? 'Laki-laki' : 'Perempuan'; ?></td>
                  <td>
                    <button class="btn-pilih-pelanggan btn btn-primary btn-block" kode_pelanggan="<?php echo $data['kode_pelanggan']; ?>" data-dismiss="modal"><span class="text"><i class="fas fa-address-card fa-sm"></i> Pilih</span></button>
                  </td>
                </tr>
                <!-- bagian akhir (penutup) while -->
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <!-- bagian akhir Tabel daftar pelanggan -->
        <!-- akhir body -->
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
  //Saat halaman diload cart ditampilkan
  $("#tampil_cart").load("page/penjualan/keranjang-belanja.php");


  //Event saat pengguna memilih pelanggan
  $('.btn-pilih-pelanggan').on('click', function() {
    var kode_pelanggan = $(this).attr("kode_pelanggan");
    $.ajax({
      url: 'page/penjualan/ambil-data-pelanggan.php',
      method: 'post',
      data: {
        kode_pelanggan: kode_pelanggan
      },
      success: function(data) {
        $('#tampil_pelanggan').html(data);
        $('#kode_pelanggan').val(kode_pelanggan);
      }
    });
  });


  //Event saat pengguna memilih produk yang ingin dibeli
  $('.btn-pilih-produk').on('click', function() {
    var kode_produk = $(this).attr("kode_produk");
    $.ajax({
      url: 'page/penjualan/ambil-produk.php',
      method: 'post',
      data: {
        kode_produk: kode_produk
      },
      success: function(data) {
        $('#tampil_deskripsi_produk').html(data);
      }
    });
  });

  //Event saat pengguna memasukan jumlah beli
  $("#jumlah_beli").bind('keyup', function() {
    var jumlah_beli = $('#jumlah_beli').val();
    var stok_produk = $('#stok_produk').val();

    var jum_beli = parseInt(jumlah_beli);
    var jum_max = parseInt(stok_produk);

    if (jum_beli != 0 && jum_beli <= jum_max) {
      document.getElementById("tambahkan_ke_keranjang").disabled = false;
      $('#info_ketersediaan').hide();
    } else {
      document.getElementById("tambahkan_ke_keranjang").disabled = true;
      $('#info_ketersediaan').show();
      $('#info_ketersediaan').html("<div class='alert alert-danger'>Jumlah beli melebihi stok produk</div>");
    }
  });

  //Event saat tombol tambahkan keranjang dklik
  $('#tambahkan_ke_keranjang').on('click', function() {
    var aksi = $(this).attr("aksi");
    var jumlah_beli = $("#jumlah_beli").val();
    var stok_produk = $("#stok_produk").val();
    var kode_produk = $("#kode_produk").val();

    $.ajax({
      url: 'page/penjualan/keranjang-belanja.php',
      method: 'POST',
      data: {
        jumlah_beli: jumlah_beli,
        stok_produk: stok_produk,
        kode_produk: kode_produk,
        aksi: aksi
      },
      success: function(data) {
        $('#tampil_cart').html(data);
      }
    });
  });
</script>