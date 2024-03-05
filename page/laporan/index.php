<div class="container-fluid">
  <!--Bagian heading -->
  <h1 class="h3 mb-2 text-gray-800">Laporan Penjualan Berdasarkan <?php echo ucfirst($_GET['isi']); ?></h1>
  <p class="mb-4">Halaman laporan berisi informasi seluruh laporan yang dapat di kelola oleh admin.</p>
  <a href="index.php?page=dashboard"><button class="btn btn-primary"><i class="fa fa-angle-left"></i> Halaman Dashboard</button></a>
  <br /><br />
  <input type="hidden" name="isi_laporan" id="isi_laporan" value="<?php echo $_GET['isi']; ?>" ?>

  <div class="card shadow mb-4">
    <div class="card-body">
      <div id="filter_laporan" class="collapse show">
        <!-- form -->
        <form method="post" id="form">
          <div class="form-row">
            <div class="col-sm-3">
              <input type="date" class="form-control" name="dari_tanggal" required>
            </div>
            <div class="col-sm-3">
              <input type="date" class="form-control" name="sampai_tanggal" required>
            </div>
            <div class="col-sm-3">
              <button type="button" id="btn-tampil" class="btn btn-dark"><span class="text"><i class="fas fa-search fa-sm"></i> Tampilkan</span></button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>

  <div id="tampil_laporan">
    <!-- Tampil Laporan menggunakan AJAX -->
  </div>
  <div id='ajax-wait'>
    <img alt='loading...' src='assets/img/Rolling-1s-84px.png' />
  </div>

  <style>
    #ajax-wait {
      display: none;
      position: fixed;
      z-index: 1999
    }
  </style>
</div>


<script type="text/javascript">
  //Fungsi untuk menampilkan isi laporan
  $('#btn-tampil').on('click', function() {
    //Memanggil fungsi loading()
    loading();

    var isi_laporan = $('#isi_laporan').val();

    switch (isi_laporan) {
      case 'item':
        laporan_per_item()
        break;
      case 'produk':
        laporan_per_produk()
        break;
      case 'kasir':
        laporan_per_kasir()
        break;
    }
  });

  //Fungsi untuk efek loading
  function loading() {
    $(document).ajaxStart(function() {
        $("#ajax-wait").css({
          left: ($(window).width() - 32) / 2 + "px", // 32 = lebar gambar
          top: ($(window).height() - 32) / 2 + "px", // 32 = tinggi gambar
          display: "block"
        })
      })
      .ajaxComplete(function() {
        $("#ajax-wait").fadeOut();
      });
  }

  //Fungsi laporan per item transaksi
  function laporan_per_item() {
    var data = $('#form').serialize();
    $.ajax({
      type: 'POST',
      url: "page/laporan/per-item/laporan-per-item.php",
      data: data,
      cache: false,
      success: function(data) {
        $("#tampil_laporan").html(data);

      }
    });
  }

  //Fungsi laporan per produk
  function laporan_per_produk() {
    var data = $('#form').serialize();
    $.ajax({
      type: 'POST',
      url: "page/laporan/per-produk/laporan-per-produk.php",
      data: data,
      cache: false,
      success: function(data) {
        $("#tampil_laporan").html(data);

      }
    });
  }

  //Fungsi laporan per kasir
  function laporan_per_kasir() {
    var data = $('#form').serialize();
    $.ajax({
      type: 'POST',
      url: "page/laporan/per-kasir/laporan-per-kasir.php",
      data: data,
      cache: false,
      success: function(data) {
        $("#tampil_laporan").html(data);

      }
    });
  }
</script>