<?php
session_start();   
     if (isset($_POST['edit_kategori'])) {
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
        //Mengambil nama dan id kategori produk
        $id_kt_produk=input($_POST["id_kt_produk"]);
        $nama_kt_produk=input($_POST["nama_kt_produk"]);
     

        //Menjalankan query update kategori_produk
        $edit_kategori=mysqli_query($kon,"update kategori_produk set nama_kt_produk='$nama_kt_produk' where id_kt_produk=$id_kt_produk");

        //Simpan aktivitas
        $id_pengguna=$_SESSION['id_pengguna'];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Edit Kategori Produk ID #$id_kt_produk ";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");


        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($edit_kategori and $simpan_aktivitas) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../index.php?page=kategori_produk&edit=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../index.php?page=kategori_produk&edit=gagal");

        }

    }
?>
<?php
    $id_kt_produk=$_POST["id_kt_produk"];
    // mengambil data barang dengan kode paling besar
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM kategori_produk where id_kt_produk=$id_kt_produk");
    $data = mysqli_fetch_array($query); 
    $nama_kt_produk=$data['nama_kt_produk'];
?>
<form action="page/produk/kategori/edit-kategori.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                    <input name="id_kt_produk" value="<?php echo $id_kt_produk; ?>" type="hidden" class="form-control">
            </div>

            <div class="form-group">
                    <label>Nama Produk:</label>
                    <input name="nama_kt_produk" value="<?php echo $nama_kt_produk; ?>" type="text" class="form-control" placeholder="Masukan nama kategori" required>
            </div>
        </div>
    </div>
    <button type="submit" name='edit_kategori'class="btn btn-primary">Update</button>
</form>

