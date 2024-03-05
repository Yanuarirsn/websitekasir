<?php
 session_start();
    if (isset($_POST['edit_pelanggan'])) {
        //Koneksi database
        include '../../config/database.php';
        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //Mengambil nilai yang dikirim dari form
        $id_pelanggan=input($_POST["id_pelanggan"]);
        $kode_pelanggan=input($_POST["kode_pelanggan"]);
        $nama_pelanggan=input($_POST["nama_pelanggan"]);
        $no_telp=input($_POST["no_telp"]);
        $alamat=input($_POST["alamat"]);
        $jk=input($_POST["jk"]);
        $tanggal_lahir=input($_POST["tanggal_lahir"]);
        $status=input($_POST["status"]);

        //Query input menginput data kedalam tabel pelanggan
        $sql="update pelanggan set
        nama_pelanggan='$nama_pelanggan',
        no_telp='$no_telp',
        alamat_pelanggan='$alamat',
        jenis_kelamin='$jk',
        tanggal_lahir='$tanggal_lahir',
        status='$status'
        where id_pelanggan=$id_pelanggan";

        //Mengeksekusi query 
        $update_pelanggan=mysqli_query($kon,$sql);

        //Tambah aktivitas
        $id_pengguna=$_SESSION['id_pengguna'];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Edit Pelanggan #$kode_pelanggan ";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($update_pelanggan and $simpan_aktivitas) {
          mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=pelanggan&edit=berhasil");
        }
        else {
          mysqli_query($kon,"ROLLBACK");
            header("Location:../../index.php?page=pelanggan&edit=gagal");

        }
        
    }
?>


<?php   
    $id_pelanggan=$_POST["id_pelanggan"];
    // mengambil data pelanggan dengan kode paling besar
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM pelanggan where id_pelanggan=$id_pelanggan");
    $data = mysqli_fetch_array($query); 

    $kode_pelanggan=$data['kode_pelanggan'];
    $nama_pelanggan=$data['nama_pelanggan'];
    $no_telp=$data['no_telp'];
    $alamat_pelanggan=$data['alamat_pelanggan'];
    $jenis_kelamin=$data['jenis_kelamin'];
    $tanggal_lahir=$data['tanggal_lahir'];
    $status=$data['status'];
?>
<form action="page/pelanggan/edit-pelanggan.php" method="post">
  <div class="form-group">
      <label>Kode pelanggan:</label>
      <h3><?php echo $kode_pelanggan; ?></h3>
  </div>
  <div class="form-group">
      <input name="kode_pelanggan" value="<?php echo $kode_pelanggan; ?>" type="hidden" class="form-control">
      <input name="id_pelanggan" value="<?php echo $id_pelanggan; ?>" type="hidden" class="form-control">
  </div>

  <div class="form-group">
      <label>Nama pelanggan:</label>
      <input name="nama_pelanggan" value="<?php echo $nama_pelanggan; ?>" type="text" class="form-control" placeholder="Masukan nama" required>
  </div>

  <div class="form-group">
      <label>No Telp:</label>
      <input name="no_telp" value="<?php echo $no_telp; ?>" type="text" class="form-control" placeholder="Masukan no telp" required>
  </div>

  <div class="form-group">
    <label>Alamat:</label>
    <textarea name="alamat" class="form-control" rows="3" ><?php echo $alamat_pelanggan; ?></textarea>
  </div>

  <div class="form-group">
  <label>Jenis Kelamin:</label>
    <div class="form-check-inline">
      <label class="form-check-label">
        <input type="radio" <?php if (isset($jenis_kelamin) && $jenis_kelamin==1) echo "checked"; ?> class="form-check-input" name="jk" value="1" required>Laki-laki
      </label>
    </div>
    <div class="form-check-inline">
      <label class="form-check-label">
        <input type="radio" <?php if (isset($jenis_kelamin) && $jenis_kelamin==2) echo "checked"; ?> class="form-check-input" name="jk" value="2" required>Perempuan
      </label>
    </div>
  </div>

  <div class="form-group">
      <label>Tanggal Lahir:</label>
      <input name="tanggal_lahir"  value="<?php echo $tanggal_lahir; ?>" type="date" class="form-control">
  </div>

  <div class="form-group">
    <label>Status:</label>
    <select name="status" class="form-control">
      <option <?php if ($status==1) echo "selected"; ?> value="1">Aktif</option>
      <option <?php if ($status==0) echo "selected"; ?> value="0">Tidak Aktif</option>
    </select>
  </div>

  <button type="submit" name="edit_pelanggan" class="btn btn-dark">Update Pelanggan</button>
</form>

