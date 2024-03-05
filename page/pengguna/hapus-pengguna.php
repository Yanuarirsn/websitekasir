<?php
session_start();
      if (isset($_POST['hapus_pengguna'])) {

        //Include file koneksi, untuk koneksikan ke database
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

        //Mengambil kiriman form
        $id_pengguna=input($_POST["id_pengguna"]);
        $kode_pengguna=input($_POST["kode_pengguna"]);
        $level=input($_POST["level"]);
        $foto=input($_POST["foto"]);


        //Mengeksekusi query 
        $hapus_pengguna=mysqli_query($kon,"delete from pengguna where id_pengguna=$id_pengguna");

        //Menghapus file foto jika foto selain foto default
        if ($foto!='pengguna_default.png'){
            unlink("foto/".$foto);
        }

        //Menyimpan aktivitas
        $id_pengguna=$_SESSION["id_pengguna"];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Hapus pengguna #$kode_pengguna ";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");
    

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hapus_pengguna and $simpan_aktivitas) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=pengguna&hapus=berhasil&pengguna=$level");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../index.php?page=pengguna&hapus=gagal&pengguna=$level");
        }

    }
?>
<form action="page/pengguna/hapus-pengguna.php" method="post">
        <!-- rows -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                     <h5>Apakah anda yakin ingin menghapus pengguna ini?</h5>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_pengguna" value="<?php echo $_POST["id_pengguna"]; ?>" />
        <input type="hidden" name="kode_pengguna" value="<?php echo $_POST["kode_pengguna"]; ?>" />
        <input type="hidden" name="level" value="<?php echo $_POST["level"]; ?>" />
        <input type="hidden" name="foto" value="<?php echo $_POST["foto"]; ?>" />
        <button type="submit" name="hapus_pengguna" class="btn btn-primary">Hapus</button>
</form>

