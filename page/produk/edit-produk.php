<?php
session_start();
if (isset($_POST['edit_produk'])) {
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
    $nama_produk=input($_POST["nama_produk"]);
    $satuan=input($_POST["satuan"]);
    $kategori_produk=input($_POST["kategori_produk"]);
    $supplier=input($_POST["supplier"]);
    $stok_produk=input($_POST["stok_produk"]);
    $harga_beli=input($_POST["harga_beli"]);
    $harga_jual=input($_POST["harga_jual"]);
    $keterangan_produk=input($_POST["keterangan_produk"]);
    $gambar_saat_ini=$_POST['gambar_saat_ini'];
    $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
    $gambar_baru = $_FILES['gambar_baru']['name'];
    $x = explode('.', $gambar_baru);
    $ekstensi = strtolower(end($x));
    $file_tmp = $_FILES['gambar_baru']['tmp_name'];	


    if (!empty($gambar_baru)){
        if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
            //Mengupload gambar baru
            move_uploaded_file($file_tmp, 'gambar/'.$gambar_baru);

            //Menghapus gambar lama, gambar yang dihapus selain gambar default
            if ($gambar_saat_ini!='gambar_default.png'){
                unlink("gambar/".$gambar_saat_ini);
            }
            
            $sql="update produk set
            nama_produk='$nama_produk',
            satuan='$satuan',
            kategori_produk='$kategori_produk',
            supplier='$supplier',
            stok_produk='$stok_produk',
            harga_beli='$harga_beli',
            harga_jual='$harga_jual',
            keterangan_produk='$keterangan_produk',
            gambar_produk='$gambar_baru'
            where id_produk=$id_produk";
        }
    }else {
        $sql="update produk set
        nama_produk='$nama_produk',
        satuan='$satuan',
        kategori_produk='$kategori_produk',
        supplier='$supplier',
        stok_produk='$stok_produk',
        harga_beli='$harga_beli',
        harga_jual='$harga_jual',
        keterangan_produk='$keterangan_produk'
        where id_produk=$id_produk";
    }


    //Mengeksekusi atau menjalankan query diatas
    $edit_produk=mysqli_query($kon,$sql);

    //Menambah aktivitas
    $id_pengguna=$_SESSION['id_pengguna'];
    $waktu=date("Y-m-d H:i:s");
    $log_aktivitas="Edit Produk #$kode_produk ";
    $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");


    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($edit_produk and $simpan_aktivitas) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=produk&edit=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=produk&edit=gagal");

    }

}

    //-----------------------------------------------------------------------------------------------------------------
    $id_produk=$_POST["id_produk"];
    // mengambil data barang dengan kode paling besar
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM produk where id_produk=$id_produk");
    $data = mysqli_fetch_array($query); 

    $kode_produk=$data['kode_produk'];
    $nama_produk=$data['nama_produk'];
    $kategori_produk=$data['kategori_produk'];
    $supplier=$data['supplier'];
    $satuan=$data['satuan'];
    $stok_produk=$data['stok_produk'];
    $harga_beli=$data['harga_beli'];
    $harga_jual=$data['harga_jual'];
    $gambar_produk=$data['gambar_produk'];
    $keterangan_produk=$data['keterangan_produk'];


?>
<form action="page/produk/edit-produk.php" method="post" enctype="multipart/form-data">
        <!-- rows -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                      <input name="kode_produk" value="<?php echo $kode_produk; ?>" type="hidden" class="form-control">
                      <input name="id_produk" value="<?php echo $id_produk; ?>" type="hidden" class="form-control">
                </div>
                <div class="form-group">
                      <label>Nama Produk:</label>
                      <input name="nama_produk" value="<?php echo $nama_produk; ?>" type="text" class="form-control" placeholder="Masukan nama" required>
                </div>
            </div>
        </div>
 
        <!-- rows -->
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Satuan:</label>
                    <input name="satuan" value="<?php echo $satuan; ?>" type="text" class="form-control" placeholder="Masukan satuan" required>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                        <label>Kategori:</label>
                        <select name="kategori_produk" class="form-control">
                        <!-- Menampilkan daftar kategori produk di dalam select list -->
                        <?php
                            include 'config/database.php';
                            $sql="select * from kategori_produk order by id_kt_produk asc";
                            $hasil=mysqli_query($kon,$sql);
                            $no=0;
                            if ($kategori_produk==0) echo "<option value='0'>-</option>";
                            while ($data = mysqli_fetch_array($hasil)):
                            $no++;
                        ?>
                            <option  <?php if ($kategori_produk==$data['id_kt_produk']) echo "selected"; ?> value="<?php echo $data['id_kt_produk']; ?>"><?php echo $data['nama_kt_produk']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Stok:</label>
                    <input name="stok_produk" value="<?php echo $stok_produk; ?>" type="number" class="form-control" placeholder="Masukan jumlah stok" required>
                </div>
            </div>
        </div>

        <!-- rows -->                 
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Harga Beli:</label>
                    <input name="harga_beli" value="<?php echo $harga_beli; ?>" type="number" class="form-control" placeholder="Masukan harga beli" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Harga Jual:</label>
                    <input name="harga_jual" value="<?php echo $harga_jual; ?>" type="number" class="form-control" placeholder="Masukan harga jual" required>
                </div>
            </div>
        </div>

        <!-- rows -->   
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Dari Suplier:</label>
                    <select name="supplier" class="form-control">
                        <!-- Menampilkan daftar kategori produk di dalam select list -->
                        <?php
                        $sql="select * from supplier order by id_supplier desc";
                        $hasil=mysqli_query($kon,$sql);
                        if ($supplier==0) echo "<option value='0'>-</option>";
                        while ($rows = mysqli_fetch_array($hasil)):
                            
                        ?>
                        <option  <?php if ($supplier==$rows['id_supplier']) echo "selected"; ?> value="<?php echo $rows['id_supplier']; ?>"><?php echo $rows['nama_supplier']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- rows -->                 
        <div class="row">
            <div class="col-sm-6">
                <label>Gambar Saat ini:</label><br>
                <img src="page/produk/gambar/<?php echo $gambar_produk;?>" width="90%" class="rounded" alt="Cinque Terre">
                <input type="hidden" name="gambar_saat_ini" value="<?php echo $gambar_produk;?>" class="form-control" />
            </div>
            <div class="col-sm-6">
                <div id="msg"></div>
                <label>Gambar Baru:</label>
                <input type="file" name="gambar_baru" class="file" >
                    <div class="input-group my-3">
                        <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                        <div class="input-group-append">
                                <button type="button" id="pilih_gambar" class="browse btn btn-dark">Pilih</button>
                        </div>
                    </div>
                <img src="assets/img/img80.png" id="preview" class="img-thumbnail">
            </div>
        </div>
        
        <!-- rows -->   
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Keterangan:</label>
                    <textarea name="keterangan_produk" class="form-control" rows="4" ><?php echo $keterangan_produk; ?></textarea>
                </div>
            </div>
        </div>
      
        <button type="submit" name="edit_produk" class="btn btn-primary">Update</button>
</form>

<style>
    .file {
    visibility: hidden;
    position: absolute;
    }
</style>

<script>
    $(document).on("click", "#pilih_gambar", function() {
    var file = $(this).parents().find(".file");
    file.trigger("click");
    });
    $('input[type="file"]').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    reader.onload = function(e) {
        // get loaded data and render thumbnail.
        document.getElementById("preview").src = e.target.result;
    };
    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
    });
</script>
