<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil</h1>
    </div>
    <?php
    //Mengecek nilai variabel edit untuk menampilkan pemberitahuan berhasil atau gagal
    if (isset($_GET['edit'])) {
        if ($_GET['edit'] == 'berhasil') {
            echo "<div class='alert alert-success'><strong>Berhasil!</strong> Profil telah diupdate</div>";
        } else if ($_GET['edit'] == 'gagal') {
            echo "<div class='alert alert-danger'><strong>Gagal!</strong> Profil gagal diupdate!</div>";
        }
    }
    ?>
    <div class="row">
    <div class="col-xl-8 col-lg-7 mx-auto">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Profil Pengguna</h6>
                </div>

                <?php
                //Mengambil data pengguna di database
                include 'config/database.php';
                $id_pengguna = $_SESSION["id_pengguna"];
                $sql = "select * from pengguna where id_pengguna=$id_pengguna limit 1";
                $hasil = mysqli_query($kon, $sql);
                $data = mysqli_fetch_array($hasil);
                ?>
                <!-- Card Body -->
                <div class="card-body d-flex flex-row align-items-center">
                <div style="margin-right: 20px;">
                    <img class="card-img-top" src="page/pengguna/foto/<?php echo $data['foto']; ?>" alt="Card image">
                </div>

                <div>
                    
                    <table class="table">
                        <tbody>
                            
                            <tr>
                                <td>Kode</td>
                                <td width="80%">: <?php echo $data['kode_pengguna']; ?></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td width="80%">: <?php echo $data['nama_pengguna']; ?></td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td width="80%">: <?php echo $data['username']; ?></td>
                            </tr>
                            <tr>
                                <td>No Telp</td>
                                <td width="80%">: <?php echo $data['no_telp']; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td width="80%">: <?php echo $data['email']; ?></td>
                            </tr>
                            <tr>
                                <td>Sebagai</td>
                                <td width="80%">: <?php echo $data['level']; ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td width="80%">: <?php echo $data['kode_pengguna']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#ubah_profil">Edit Profil</button>
                </div>
            </div>
        </div>


        <div class="col-xl-8 col-lg-7">
            
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- The Modal -->
<div class="modal fade" id="ubah_profil">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Ubah Profil</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form action="page/profil/edit-profil.php" method="post" enctype="multipart/form-data">

                    <div class="card">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Data Diri</h6>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Kode:</label>
                                        <input name="kode" value="<?php echo $data['kode_pengguna'] ?>" type="text" class="form-control" placeholder="Masukan kode" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama:</label>
                                        <input name="nama" value="<?php echo $data['nama_pengguna'] ?>" type="text" class="form-control" placeholder="Masukan nama" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input name="email" id="email" value="<?php echo $data['email'] ?>" type="email" class="form-control" placeholder="Masukan email" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>No Telp:</label>
                                        <input name="no_telp" value="<?php echo $data['no_telp'] ?>" type="text" class="form-control" placeholder="Masukan no telp" required>
                                    </div>
                                </div>
                            </div>
                            <!-- rows -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Foto Saat ini:</label><br>
                                    <img src="page/pengguna/foto/<?php echo $data['foto']; ?>" width="90%" class="rounded" alt="Cinque Terre">
                                    <input type="hidden" name="foto_saat_ini" value="<?php echo $data['foto']; ?>" class="form-control" />
                                </div>
                                <div class="col-sm-6">
                                    <div id="msg"></div>
                                    <label>Foto Baru:</label>
                                    <input type="file" name="foto_baru" class="file">
                                    <div class="input-group my-3">
                                        <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                        <div class="input-group-append">
                                            <button type="button" id="pilih_foto" class="browse btn btn-dark">Pilih Foto</button>
                                        </div>
                                    </div>
                                    <img src="assets/img/img80.png" id="preview" class="img-thumbnail">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Data Login</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Username:</label>
                                        <input name="username_baru" id="username_baru" value="<?php echo $data['username'] ?>" type="text" class="form-control" placeholder="Masukan username" required>
                                        <input name="username_lama" id="username_lama" value="<?php echo $data['username'] ?>" type="hidden" class="form-control">
                                        <!-- Informasi ketersediaan username akan ditampilkan disini -->
                                        <div id="info_username"> </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input name="password" value="<?php echo $data['password'] ?>" type="password" class="form-control" placeholder="Masukan nama" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_pengguna" value="<?php echo $data['id_pengguna'] ?>" />
                    <button type="submit" name="simpan_profil" id="simpan_profil" class="btn btn-dark">Simpan Profil</button>
                </form>
                <!-- akhir body -->
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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

    $("#username_baru").bind('keyup', function() {
        var username_baru = $('#username_baru').val();
        var username_lama = $('#username_lama').val();

        if (username_baru != username_lama) {
            $.ajax({
                url: 'page/profil/cek-username.php',
                method: 'POST',
                data: {
                    username_baru: username_baru
                },
                success: function(data) {
                    $('#info_username').show();
                    $('#info_username').html(data);
                }
            });
        } else {
            document.getElementById("username_baru").value = username_baru;
            $('#info_username').hide();
        }

    });
</script>