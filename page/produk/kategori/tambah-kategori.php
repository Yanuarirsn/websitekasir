<?php
session_start();  
    if (isset($_POST['tambah_kategori'])) {
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
    
        $nama_kt_produk=input($_POST["nama_kt_produk"]);
        //Peintah sql untuk menambah kategori produk
        $sql="insert into kategori_produk (nama_kt_produk) values ('$nama_kt_produk')";

        //Mengeksekusi atau menjalankan query diatas
        $tambah_kt_produk=mysqli_query($kon,$sql);

        //Simpan aktivitas
        $id_pengguna=$_SESSION['id_pengguna'];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Tambah Kategori Produk : $nama_kt_produk ";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");



        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($tambah_kt_produk and $simpan_aktivitas) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../index.php?page=kategori_produk&add=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../index.php?page=kategori_produk&add=gagal");

        }

    }
?>
<form action="page/produk/kategori/tambah-kategori.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                    <label>Nama Produk:</label>
                    <input name="nama_kt_produk"  type="text" class="form-control" placeholder="Masukan nama kategori" required>
            </div>
        </div>
    </div>
    <button type="submit" name='tambah_kategori'class="btn btn-dark">Tambah</button>
</form>

