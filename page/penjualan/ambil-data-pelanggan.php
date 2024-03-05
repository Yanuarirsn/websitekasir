<?php
    include '../../config/database.php';
    $kode_pelanggan=$_POST['kode_pelanggan'];
    $query = mysqli_query($kon, "SELECT * FROM pelanggan where kode_pelanggan='$kode_pelanggan'");
    $data = mysqli_fetch_array($query);
    echo "Pelanggan : ".$data["nama_pelanggan"]." <a href=''  data-toggle='modal' data-target='#pilih_pelanggan' >Ganti</a>";

?>
