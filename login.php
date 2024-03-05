<?php
session_start();
if (isset($_SESSION["id_pengguna"])) {
  header("Location:index.php?page=dashboard");
}
$pesan = "";
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
  include "config/database.php";

  $username = input($_POST["username"]);
  $password = input(md5($_POST["password"]));

  $cek_pengguna = "select * from pengguna where username='" . $username . "' and password='" . $password . "' limit 1";
  $hasil_cek = mysqli_query($kon, $cek_pengguna);
  $jumlah = mysqli_num_rows($hasil_cek);
  $row = mysqli_fetch_assoc($hasil_cek);

  if ($jumlah > 0) {
    if ($row["status"] == 1) {
      $_SESSION["id_pengguna"] = $row["id_pengguna"];
      $_SESSION["kode_pengguna"] = $row["kode_pengguna"];
      $_SESSION["nama_pengguna"] = $row["nama_pengguna"];
      $_SESSION["username"] = $row["username"];
      $_SESSION["level"] = $row["level"];

      $id_pengguna = $row["id_pengguna"];
      $waktu = date("Y-m-d h:i:s");
      $log_aktifitas = "Login";


      mysqli_query($kon, "INSERT into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$aktivitas',$id_pengguna)");


      header("Location:index.php?page=dashboard");
    } else {
      $pesan = "<div class='alert alert-warning'><strong>Gagal!</strong> Status pengguna tidak aktif.</div>";
    }
  } else {
    $pesan = "<div class='alert alert-danger'><strong>Error!</strong> Username dan password salah.</div>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>KASIR</title>

  <!-- Custom fonts for this template-->
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="assets/font/font.css" rel="stylesheet" type="text/css">
  <link href='page/aplikasi/logo/shop.png' rel='shortcut icon'>

  <!-- Custom styles for this template-->
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>


  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-6 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <?php
            include 'config/database.php';
            $hasil = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");
            $data = mysqli_fetch_array($hasil);
            ?>
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <img src="page/aplikasi/logo/<?php echo $data['logo']; ?>" id="preview" width="35%" class="img-thumbnail">
                    <h1 class="h4 text-gray-900 mb-4"><?php echo strtoupper($data['nama_aplikasi']); ?></h1>
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") echo $pesan; ?>
                  </div>
                  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="username" placeholder="Masukan Username" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="password" placeholder="Masukan Password" required>
                    </div>

                    <button type="submit" class="btn btn-login btn-user btn-block">Login</button>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="assets/js/sb-admin-2.min.js"></script>
  <script src="assets/js/loader.js"></script>

</body>

</html>