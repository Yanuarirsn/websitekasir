<div class="container-fluid">
    <a href="index.php?page=dashboard"><button class="btn btn-primary"><i class="fa fa-angle-left"></i> Halaman Dashboard</button></a>
    <br /><br />
    <?php
    //Mengecek nilai variabel edit untuk menampilkan pemberitahuan berhasil atau gagal
    if (isset($_GET['edit'])) {

        if ($_GET['edit'] == 'berhasil') {
            echo "<div class='alert alert-success'>Profil aplikasi telah berhasil diubah</div>";
        } else if ($_GET['edit'] == 'gagal') {
            echo "<div class='alert alert-danger'>Profil aplikasi gagal diubah</div>";
        }
    }

    //Koneksi database
    include 'config/database.php';
    //Menjalankan query
    $hasil = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");
    $data = mysqli_fetch_array($hasil);

    ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4>Pengaturan Aplikasi</h4>
        </div>
        <div class="card-body">
        <div class="row justify-content-center"> 
                <div class="col-sm-5">
                    <div class="card mb-4">
                        <div class="card-header">Profil Aplikasi</div>
                        <div class="card-body">
                            <form action="page/aplikasi/edit.php" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" value="<?php echo $data['id']; ?>" name="id">
                                </div>
                                <div class="form-group">
                                    <label>Nama Toko:</label>
                                    <input type="text" class="form-control" value="<?php echo $data['nama_aplikasi']; ?>" name="nama" required>
                                </div>
                                <div class="form-group">
                                    <label>Alamat:</label>
                                    <input type="text" class="form-control" value="<?php echo $data['alamat']; ?>" name="alamat" required>
                                </div>
                                <div class="form-group">
                                    <label>No Telp:</label>
                                    <input type="text" class="form-control" value="<?php echo $data['no_telp']; ?>" name="no_telp" required>
                                </div>
                                <div class="form-group">
                                    <div id="msg"></div>
                                    <label>Logo:</label>
                                    <input type="file" name="logo" class="file">
                                    <div class="input-group my-3">
                                        <input type="text" class="form-control" disabled placeholder="Upload Gambar" id="file">
                                        <div class="input-group-append">
                                            <button type="button" id="pilih_logo" class="browse btn btn-dark">Pilih Logo</button>
                                        </div>
                                    </div>
                                    <img src="page/aplikasi/logo/<?php echo $data['logo']; ?>" id="preview" width="40%" class="img-thumbnail">
                                    <input type="hidden" name="logo_sebelumnya" value="<?php echo $data['logo']; ?>" />
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="ubah_aplikasi">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .file {
        visibility: hidden;
        position: absolute;
    }
</style>
<script>
    $(document).on("click", "#pilih_logo", function() {
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