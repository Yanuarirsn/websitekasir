<?php
session_start();
if (isset($_POST['tambah_pelanggan'])) {
  //Koneksi database
  include '../../config/database.php';

  //Memulai transaksi
  mysqli_query($kon, "START TRANSACTION");

  //Fungsi untuk mencegah inputan karakter yang tidak sesuai
  function input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  //Mengambil nilai yang dikirim dari form
  $kode_pelanggan = input($_POST["kode_pelanggan"]);
  $nama_pelanggan = input($_POST["nama_pelanggan"]);
  $no_telp = input($_POST["no_telp"]);
  $alamat = input($_POST["alamat"]);
  $jk = input($_POST["jk"]);
  $tanggal_lahir = input($_POST["tanggal_lahir"]);
  $status = input($_POST["status"]);

  //Query input menginput data kedalam tabel pelanggan
  $sql = "insert into pelanggan (kode_pelanggan,nama_pelanggan,no_telp,alamat_pelanggan,jenis_kelamin,tanggal_lahir,status) values
        ('$kode_pelanggan','$nama_pelanggan','$no_telp','$alamat','$jk','$tanggal_lahir','$status')";
  //Mengeksekusi query 
  $simpan_pelanggan = mysqli_query($kon, $sql);

  $id_pengguna = $_SESSION['id_pengguna'];
  $waktu = date("Y-m-d H:i:s");
  $log_aktivitas = "Tambah Pelanggan #$kode_pelanggan ";
  $simpan_aktivitas = mysqli_query($kon, "insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");

  //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
  if ($simpan_pelanggan and  $simpan_aktivitas) {
    mysqli_query($kon, "COMMIT");
    header("Location:../../index.php?page=pelanggan&add=berhasil");
  } else {
    mysqli_query($kon, "ROLLBACK");
    header("Location:../../index.php?page=pelanggan&add=gagal");
  }
}
?>

<?php

// mengambil data pelanggan dengan kode paling besar
include '../../config/database.php';
$query = mysqli_query($kon, "SELECT max(id_pelanggan) as kodeTerbesar FROM pelanggan");
$data = mysqli_fetch_array($query);
$id_pelanggan = $data['kodeTerbesar'];
$id_pelanggan++;
$huruf = "PN";
$kodepelanggan = $huruf . sprintf("%04s", $id_pelanggan);
?>
<form action="page/pelanggan/tambah-pelanggan.php" method="post">
  <div class="form-group">
    <label>Kode pelanggan:</label>
    <h3><?php echo $kodepelanggan; ?></h3>
    <input name="kode_pelanggan" value="<?php echo $kodepelanggan; ?>" type="hidden" class="form-control">
  </div>
  <div class="form-group">
    <label>Nama pelanggan:</label>
    <input name="nama_pelanggan" type="text" class="form-control" placeholder="Masukan nama" required>
  </div>
  <div class="form-group">
    <label>No Telp:</label>
    <input name="no_telp" type="text" class="form-control" placeholder="Masukan no telp" required>
  </div>
  <div class="form-group">
    <label>Alamat:</label>
    <textarea name="alamat" class="form-control" rows="3"></textarea>
  </div>
  <div class="form-group">
    <label>Jenis Kelamin:</label>
    <div class="form-check-inline">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="jk" value="1" required>Laki-laki
      </label>
    </div>
    <div class="form-check-inline">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="jk" value="2" required>Perempuan
      </label>
    </div>
  </div>
  <div class="form-group">
    <label>Tanggal Lahir:</label>
    <input name="tanggal_lahir" type="date" class="form-control">
  </div>
  <div class="form-group">
    <label>Status:</label>
    <select name="status" class="form-control">
      <option value="1">Aktif</option>
      <option value="0">Tidak Aktif</option>
    </select>
  </div>

  <button type="submit" name="tambah_pelanggan" class="btn btn-dark">Tambah</button>
</form>