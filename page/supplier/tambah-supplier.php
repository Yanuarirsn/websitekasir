<?php
session_start();
    if (isset($_POST['simpan_tambah'])) {
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
        //Mengambil nilai kiriman form
        $kode_supplier=input($_POST["kode_supplier"]);
        $nama_supplier=input($_POST["nama_supplier"]);
        $no_telp=input($_POST["no_telp"]);
        $alamat=input($_POST["alamat"]);
        $status=input($_POST["status"]);

        //Query input menginput data kedalam tabel supplier
        $sql="insert into supplier (kode_supplier,nama_supplier,no_telp,alamat_supplier,status) values
        ('$kode_supplier','$nama_supplier','$no_telp','$alamat','$status')";

        //Mengeksekusi query 
        $tambah_supplier=mysqli_query($kon,$sql);

        //Menambah aktivitas
        $id=$_SESSION['id_pengguna'];
        $waktu=date("Y-m-d H:i:s");
        $log_aktivitas="Tambah Supplier # $kode_supplier";
        $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas','$id')");


        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($tambah_supplier and $simpan_aktivitas) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=supplier&add=berhasil");
        }
        else {
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=supplier&add=gagal");

        }
        
    }
?>
<?php
    // mengambil data id_supplier dengan kode paling besar
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT max(id_supplier) as kodeTerbesar FROM supplier");
    $data = mysqli_fetch_array($query);
    $id_supplier = $data['kodeTerbesar'];
    $id_supplier++;
    $huruf = "SP";
    $kodesupplier = $huruf . sprintf("%04s", $id_supplier);
?>
<form action="page/supplier/tambah-supplier.php" method="post">
        <div class="form-group">
            <label>Kode supplier:</label>
            <h3><?php echo $kodesupplier; ?></h3>
            <input name="kode_supplier" value="<?php echo $kodesupplier; ?>" type="hidden" class="form-control">
        </div>
        <div class="form-group">
            <label>Nama supplier:</label>
            <input name="nama_supplier" type="text" class="form-control" placeholder="Masukan nama" required>
        </div>
        <div class="row">
            <div class="col-sm-6">
            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>No Telp:</label>
                    <input name="no_telp" type="text" class="form-control" placeholder="Masukan no telp" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat" class="form-control" rows="3" ></textarea>
        </div>
        <button type="submit" name="simpan_tambah" class="btn btn-dark">Tambah</button>
</form>

