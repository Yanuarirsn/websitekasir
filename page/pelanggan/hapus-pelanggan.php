<?php
session_start();
      if (isset($_POST['hapus_pelanggan'])) {

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
        $id_pelanggan=input($_POST["id_pelanggan"]);
        $kode_pelanggan=input($_POST["kode_pelanggan"]);


        //Mengeksekusi query 
        $hapus_pelanggan=mysqli_query($kon,"delete from pelanggan where id_pelanggan=$id_pelanggan");

        $id_pengguna=$_SESSION['id_pengguna'];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Hapus Pelanggan #$kode_pelanggan ";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hapus_pelanggan and $simpan_aktivitas) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=pelanggan&hapus=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../index.php?page=pelanggan&hapus=gagal");

        }

    }
?>
<form action="page/pelanggan/hapus-pelanggan.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                    <h5>Apakah anda yakin ingin menghapus pelanggan ini?</h5>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_pelanggan" value="<?php echo $_POST["id_pelanggan"]; ?>" />
    <input type="hidden" name="kode_pelanggan" value="<?php echo $_POST["kode_pelanggan"]; ?>" />
    <button type="submit" name="hapus_pelanggan" class="btn btn-primary">Hapus</button>
</form>
