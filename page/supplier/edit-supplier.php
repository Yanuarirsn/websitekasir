<?php
session_start();
if (isset($_POST['update_supplier'])) {
    //Koneksi database
    include '../../config/database.php';
    //Memulai transaksi
    mysqli_query($kon, "START TRANSACTION");
    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //Mengambil nilai kiriman form
    $id_supplier = input($_POST["id_supplier"]);
    $kode_supplier = input($_POST["kode_supplier"]);
    $nama_supplier = input($_POST["nama_supplier"]);
    $no_telp = input($_POST["no_telp"]);
    $alamat = input($_POST["alamat"]);
    $status = input($_POST["status"]);


    //Query update supplier
    $sql = "update supplier set
        nama_supplier='$nama_supplier',
        no_telp='$no_telp',
        alamat_supplier='$alamat',
        status='$status'
        where id_supplier=$id_supplier";

    //Menjalankan query 
    $edit_supplier = mysqli_query($kon, $sql);

    $id = $_SESSION['id_pengguna'];
    $waktu = date("Y-m-d H:i:s");
    $log_aktivitas = "Edit Supplier # $kode_supplier";
    $simpan_aktivitas = mysqli_query($kon, "insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas','$id')");


    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($edit_supplier and $simpan_aktivitas) {
        mysqli_query($kon, "COMMIT");
        header("Location:../../index.php?page=supplier&edit=berhasil");
    } else {
        mysqli_query($kon, "ROLLBACK");
        header("Location:../../index.php?page=supplier&edit=gagal");
    }
}
?>
<?php
//Mengambil id_supplier
$id_supplier = $_POST["id_supplier"];

//Koneksi datase
include '../../config/database.php';

//Mengambil data supplier berdasarkan id_supplier
$query = mysqli_query($kon, "SELECT * FROM supplier where id_supplier=$id_supplier");
$data = mysqli_fetch_array($query);

//Menyimpan ke dalam variabel
$kode_supplier = $data['kode_supplier'];
$nama_supplier = $data['nama_supplier'];
$no_telp = $data['no_telp'];
$alamat_supplier = $data['alamat_supplier'];
$status = $data['status'];
?>
<form action="page/supplier/edit-supplier.php" method="post">
    <div class="form-group">
        <label>Kode supplier:</label>
        <h3><?php echo $kode_supplier; ?></h3>
    </div>
    <div class="form-group">
        <input name="kode_supplier" value="<?php echo $kode_supplier; ?>" type="hidden" class="form-control">
        <input name="id_supplier" value="<?php echo $id_supplier; ?>" type="hidden" class="form-control">
    </div>

    <div class="form-group">
        <label>Nama supplier:</label>
        <input name="nama_supplier" value="<?php echo $nama_supplier; ?>" type="text" class="form-control" placeholder="Masukan nama">
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option <?php if ($status == 1) echo "selected"; ?> value="1">Aktif</option>
                    <option <?php if ($status == 0) echo "selected"; ?> value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>No Telp:</label>
                <input name="no_telp" type="text" value="<?php echo $no_telp; ?>" class="form-control" placeholder="Masukan no telp">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Alamat:</label>
        <textarea name="alamat" class="form-control" rows="3"><?php echo $alamat_supplier; ?></textarea>
    </div>
    <button type="submit" name="update_supplier" class="btn btn-dark">Update supplier</button>
</form>