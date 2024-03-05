<?php
    include '../../config/database.php';
    $kode_produk=$_POST['kode_produk'];
    $query = mysqli_query($kon, "SELECT * FROM produk where kode_produk='$kode_produk'");
    $data = mysqli_fetch_array($query);     
?>
<input type="hidden" id="ambil_kode" value="<?php echo $data['kode_produk']?>"/>
<input type="hidden" id="ambil_nama" value="<?php echo $data['nama_produk']?>"/>
<input type="hidden" id="ambil_harga" value="<?php echo $data['harga_jual']?>"/>
<input type="hidden" id="ambil_stok" value="<?php echo $data['stok_produk']?>"/>
<script>
    var kode =  $('#ambil_kode').val();
    var nama =  $('#ambil_nama').val();
    var harga =  $('#ambil_harga').val();
    var stok =  $('#ambil_stok').val();

    $('#kode_produk').val(kode);
    $('#nama_produk').val(nama);
    $('#harga_produk').val(harga);
    $('#stok_produk').val(stok);
    $('#info_stok_produk').text(stok);
</script>
