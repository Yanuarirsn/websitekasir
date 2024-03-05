<?php
session_start();  
      if (isset($_POST['simpan_hapus'])) {

        //Koneksi database
        include '../../../config/database.php';
        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $id_kt_produk=input($_POST["id_kt_produk"]);

        //Menghapus kategori produk dan SET id_kategori produk menjadi 0 pada kategori produk yg dihapus
        $hapus_kategori=mysqli_query($kon,"delete from kategori_produk where id_kt_produk=$id_kt_produk");
        $update_produk=mysqli_query($kon,"update produk set kategori_produk=0 where kategori_produk=$id_kt_produk");

        //Simpan aktivitas
        $id_pengguna=$_SESSION['id_pengguna'];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Hapus Kategori Produk ID : $id_kt_produk ";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");


        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hapus_kategori and $update_produk and $simpan_aktivitas) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../index.php?page=kategori_produk&hapus=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../index.php?page=kategori_produk&hapus=gagal");

        }

    }
?>
<form action="page/produk/kategori/hapus-kategori.php" method="post">
        <!-- rows -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                     <h5>Apakah anda yakin ingin menghapus kategori produk ini?</h5>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_kt_produk" value="<?php echo $_POST["id_kt_produk"]; ?>" />
        <button type="submit" name="simpan_hapus" class="btn btn-primary">Hapus</button>
</form>

