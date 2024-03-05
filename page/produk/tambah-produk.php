<?php
session_start();
    if (isset($_POST['tambah_produk'])) {
        //Koneksi database
        include '../../config/database.php';
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        //Cek apakah ada kiriman form dari method post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Memulai transaksi
            mysqli_query($kon,"START TRANSACTION");

            $kode_produk=input($_POST["kode_produk"]);
            $nama_produk=ucwords(input($_POST["nama_produk"]));
            $satuan=input($_POST["satuan"]);
            $kategori_produk=input($_POST["kategori_produk"]);
            $stok_produk=input($_POST["stok_produk"]);
            $harga_beli=input($_POST["harga_beli"]);
            $harga_jual=input($_POST["harga_jual"]);
            $supplier=input($_POST["supplier"]);
            $keterangan_produk=input($_POST["keterangan_produk"]);
            $tanggal=date("Y-m-d");
            $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
            $gambar_produk = $_FILES['gambar_produk']['name'];
            $x = explode('.', $gambar_produk);
            $ekstensi = strtolower(end($x));
            $file_tmp = $_FILES['gambar_produk']['tmp_name'];

            //Validasi jika gambar produk tidak diinput oleh user
            if (!empty($gambar_produk)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){

                    //Mengupload gambar
                    move_uploaded_file($file_tmp, 'gambar/'.$gambar_produk);

                    $sql="insert into produk (kode_produk,nama_produk,satuan,kategori_produk,stok_produk,supplier,harga_beli,harga_jual,keterangan_produk,tanggal_produk,gambar_produk) values
                    ('$kode_produk','$nama_produk','$satuan','$kategori_produk','$stok_produk','$supplier','$harga_beli','$harga_jual','$keterangan_produk','$tanggal','$gambar_produk')";
     
                }
            }else {
                $sql="insert into produk (kode_produk,nama_produk,satuan,kategori_produk,stok_produk,supplier,harga_beli,harga_jual,keterangan_produk,tanggal_produk,gambar_produk) values
                ('$kode_produk','$nama_produk','$satuan','$kategori_produk','$stok_produk','$supplier','$harga_beli','$harga_jual','$keterangan_produk','$tanggal','gambar_default.png')";

            }

            //Mengeksekusi query 
            $simpan_produk=mysqli_query($kon,$sql);

            //Tambah aktivitas
            $id_pengguna=$_SESSION['id_pengguna'];
            $waktu=date("Y-m-d H:i:s");
            $log_aktivitas="Tambah Produk #$kode_produk ";
            $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");

            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($simpan_produk and $simpan_aktivitas) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=produk&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=produk&add=gagal");
            }

        }

    }


    // mengambil data produk dengan kode paling besar
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT max(id_produk) as kodeTerbesar FROM produk");
    $data = mysqli_fetch_array($query);
    $id_produk = $data['kodeTerbesar'];
    $id_produk++;
    $huruf = "P";
    $kodeProduk = $huruf . sprintf("%04s", $id_produk);

?>
<form action="page/produk/tambah-produk.php" method="post" enctype="multipart/form-data">
    <!-- rows -->
    <div class="row">
        <div class="col-sm-10">
            <div class="form-group">
                <label>Nama Produk:</label>
                <input name="nama_produk" type="text" class="form-control" placeholder="Masukan nama" required>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Kode Produk:</label>
                <h3><?php echo $kodeProduk; ?></h3>
                <input name="kode_produk" value="<?php echo $kodeProduk; ?>" type="hidden" class="form-control">
            </div>
        </div>
    </div>

    <!-- rows -->
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Satuan:</label>
                <input name="satuan" type="text" class="form-control" placeholder="Masukan satuan" required>
            </div>
        </div>
    <div class="col-sm-4">
        <div class="form-group">
                <label>Kategori:</label>
                <select name="kategori_produk" class="form-control">
                <!-- Menampilkan daftar kategori produk di dalam select list -->
                <?php
                 
                    $sql="select * from kategori_produk order by id_kt_produk asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option value="<?php echo $data['id_kt_produk']; ?>"><?php echo $data['nama_kt_produk']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Stok:</label>
            <input name="stok_produk" type="number" class="form-control" placeholder="Masukan jumlah stok" required>
        </div>
    </div>
    </div>
    <!-- rows -->                 
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Harga Beli:</label>
                <input name="harga_beli" type="number" class="form-control" placeholder="Masukan harga beli" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Harga Jual:</label>
                <input name="harga_jual" type="number" class="form-control" placeholder="Masukan harga jual" required>
            </div>
        </div>
    </div>
    <!-- rows -->   
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div id="msg"></div>
                <label>Gambar Produk:</label>
                <input type="file" name="gambar_produk" class="file" >
                    <div class="input-group my-3">
                        <input type="text" class="form-control" disabled placeholder="Upload Gambar" id="file">
                        <div class="input-group-append">
                                <button type="button" id="pilih_gambar" class="browse btn btn-dark">Pilih</button>
                        </div>
                    </div>
                <img src="assets/img/img80.png" id="preview" class="img-thumbnail">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Dari Suplier:</label><br>
                <select name="supplier" class="form-control">
                    <!-- Menampilkan daftar supplier di dalam select list -->
                    <?php
                    $sql="select * from supplier order by id_supplier desc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                    ?>
                    <option value="<?php echo $data['id_supplier']; ?>"><?php echo $data['nama_supplier']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- rows -->   
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Keterangan:</label>
                <textarea name="keterangan_produk" class="form-control" rows="4" ></textarea>
            </div>
        </div>
    </div>

        <button type="submit" name="tambah_produk" class="btn btn-dark">Tambah</button>
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