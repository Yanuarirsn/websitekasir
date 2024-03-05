<?php
session_start();
      if (isset($_POST['hapus_supplier'])) {

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

        //Mengambil id_supplier dan kode supplier
        $id_supplier=input($_POST["id_supplier"]);
        $kode_supplier=input($_POST["kode_supplier"]);
        //Query untuk hapus supplier dan update supplier di tabel produk
        $sql1="delete from supplier where id_supplier=$id_supplier";
        $sql2="update produk set supplier=0 where supplier=$id_supplier";

        //Menjalankan query
        $hasil1=mysqli_query($kon,$sql1);
        $hasil2=mysqli_query($kon,$sql2);

        $id=$_SESSION['id_pengguna'];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Hapus Supplier # $kode_supplier";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas','$id')");



        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query
        if ($hasil1 and $hasil2 and $simpan_aktivitas) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=supplier&hapus=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../index.php?page=supplier&hapus=gagal");

        }

    }
?>
<form action="page/supplier/hapus-supplier.php" method="post">
        <!-- rows -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                     <h5>Apakah anda yakin ingin menghapus supplier ini?</h5>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_supplier" value="<?php echo $_POST["id_supplier"]; ?>" />
        <input type="hidden" name="kode_supplier" value="<?php echo $_POST["kode_supplier"]; ?>" />
        <button type="submit" name="hapus_supplier" class="btn btn-primary">Hapus</button>
</form>

