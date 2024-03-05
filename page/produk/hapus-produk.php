<?php
session_start();
      if (isset($_POST['hapus_produk'])) {
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
        //Mengambil nilai yang dikirim
        $id_produk=input($_POST["id_produk"]);
        $kode_produk=input($_POST["kode_produk"]);
        $gambar_produk=$_POST["gambar_produk"];

        $sql="delete from produk where id_produk=$id_produk";
        $hapus_produk=mysqli_query($kon,$sql);

        //Menghapus file gambar jika gambar selain gambar default
        if ($gambar_produk!='gambar_default.png'){
            unlink("gambar/".$gambar_produk);
        }

        $id_pengguna=$_SESSION['id_pengguna'];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Hapus Produk #$kode_produk ";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");


        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hapus_produk and $simpan_aktivitas) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=produk&hapus=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../index.php?page=produk&hapus=gagal");

        }

    }
?>
<form action="page/produk/hapus-produk.php" method="post">
        <!-- rows -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                     <h5>Apakah anda yakin ingin menghapus produk ini?</h5>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_produk" value="<?php echo $_POST["id_produk"]; ?>" />
        <input type="hidden" name="kode_produk" value="<?php echo $_POST["kode_produk"]; ?>" />
        <input type="hidden" name="gambar_produk" value="<?php echo $_POST["gambar_produk"]; ?>" />
        <button type="submit" name="hapus_produk" class="btn btn-primary">Hapus</button>
</form>

