<?php
    //Mengambil nilai id_produk
    $id_produk=$_POST["id_produk"];
    //Koneksi database
    include '../../config/database.php';
    // mengambil data produk dengan kode paling besar
    $query = mysqli_query($kon, "SELECT gambar_produk FROM produk where id_produk=$id_produk");
    $data = mysqli_fetch_array($query); 

    $gambar_produk=$data['gambar_produk'];

?>
<!-- rows -->
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
        <center><img src="page/produk/gambar/<?php echo $gambar_produk;?>" width="85%" class="rounded"></center>
        </div>
    </div>
</div>

 