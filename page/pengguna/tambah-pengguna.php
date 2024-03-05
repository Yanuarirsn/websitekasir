<?php
session_start();
if (isset($_POST['tambah_pengguna'])) {

    //Include file koneksi, untuk koneksikan ke database
    include '../../config/database.php';

    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Memulai transaksi
        mysqli_query($kon, "START TRANSACTION");

        $kode_pengguna = input($_POST["kode_pengguna"]);
        $nama_pengguna = input($_POST["nama_pengguna"]);
        $email = input($_POST["email"]);
        $no_telp = input($_POST["no_telp"]);
        $username = input($_POST["username"]);
        $password = md5(input($_POST["password"]));
        $level = input($_POST["level"]);
        $status = input($_POST["status"]);
        $ekstensi_diperbolehkan    = array('png', 'jpg', 'jpeg', 'gif');
        $foto = $_FILES['foto']['name'];
        $x = explode('.', $foto);
        $ekstensi = strtolower(end($x));
        $ukuran    = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];

        if (!empty($foto)) {
            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                //Mengupload gambar
                move_uploaded_file($file_tmp, 'foto/' . $foto);
                //Sql jika menggunakan foto
                $sql = "insert into pengguna (kode_pengguna,nama_pengguna,email,no_telp,foto,username,password,level,status) values
                        ('$kode_pengguna','$nama_pengguna','$email','$no_telp','$foto','$username','$password','$level','$status')";
            }
        } else {
            //Sql jika tidak menggunakan foto, maka akan memakai gambar pengguna_default.png
            $sql = "insert into pengguna (kode_pengguna,nama_pengguna,email,no_telp,foto,username,password,level,status) values
                ('$kode_pengguna','$nama_pengguna','$email','$no_telp','pengguna_default.png','$username','$password','$level','$status')";
        }

        //Mengeksekusi query 
        $simpan_pengguna = mysqli_query($kon, $sql);

        //Menyimpan aktivitas
        $id_pengguna = $_SESSION["id_pengguna"];
        $waktu = date("Y-m-d H:i:s");
        $log_aktivitas = "Tambah Pengguna ($level) #$kode_pengguna ";
        $simpan_aktivitas = mysqli_query($kon, "insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($simpan_pengguna and $simpan_aktivitas) {
            mysqli_query($kon, "COMMIT");
            header("Location:../../index.php?page=pengguna&add=berhasil&pengguna=$level");
        } else {
            mysqli_query($kon, "ROLLBACK");
            header("Location:../../index.php?page=pengguna&add=gagal&pengguna=$level");
        }
    }
}
?>

<?php
// mengambil data pengguna dengan kode paling besar
include '../../config/database.php';
$query = mysqli_query($kon, "SELECT max(id_pengguna) as kodeTerbesar FROM pengguna");
$data = mysqli_fetch_array($query);
$id_pengguna = $data['kodeTerbesar'];
$id_pengguna++;
$huruf = "PG";
$kodepengguna = $huruf . sprintf("%03s", $id_pengguna);
?>
<form action="page/pengguna/tambah-pengguna.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Kode pengguna:</label>
        <h3><?php echo $kodepengguna; ?></h3>
        <input name="kode_pengguna" value="<?php echo $kodepengguna; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama pengguna:</label>
        <input name="nama_pengguna" type="text" class="form-control" placeholder="Masukan nama" required>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Email:</label>
                <input name="email" type="email" class="form-control" placeholder="Masukan email" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>No Telp:</label>
                <input name="no_telp" type="text" class="form-control" placeholder="Masukan no telp" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Username:</label>
                <input name="username" type="text" id="username" class="form-control" placeholder="Masukan username" required>
                <div id="info_username"> </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Password:</label>
                <input name="password" type="password" class="form-control" placeholder="Masukan password" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Level Pengguna:</label>
                <select name="level" class="form-control">
                    <option value="<?php echo $_POST['pengguna']; ?>"><?php echo $_POST['pengguna']; ?></option>

                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div id="msg"></div>
                <label>Foto:</label>
                <input type="file" name="foto" class="file">
                <div class="input-group my-3">
                    <input type="text" class="form-control" disabled placeholder="Upload Foto" id="file">
                    <div class="input-group-append">
                        <button type="button" id="pilih_foto" class="browse btn btn-dark">Pilih Foto</button>
                    </div>
                </div>
                <img src="assets/img/img80.png" id="preview" class="img-thumbnail">
            </div>
        </div>
    </div>
    <button type="submit" name="tambah_pengguna" id="tambah_pengguna" class="btn btn-dark" disabled>Tambah</button>
</form>

<style>
    .file {
        visibility: hidden;
        position: absolute;
    }
</style>

<script>
    $(document).on("click", "#pilih_foto", function() {
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

    $("#username").bind('keyup', function() {

        var username = $('#username').val();

        $.ajax({
            url: 'page/pengguna/cek-username.php',
            method: 'POST',
            data: {
                username: username
            },
            success: function(data) {
                $('#info_username').show();
                $('#info_username').html(data);
            }
        });

    });
</script>