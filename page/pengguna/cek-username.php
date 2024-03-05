<?php
    //Koneksi database
    include '../../config/database.php';
    //Mengambil username
    $username=$_POST['username'];

    //Kondisi jika usrname kosong
    if (empty($username)){
        echo "<p class='text-warning'>Username tidak boleh kosong</p>";
        echo "<script>   document.getElementById('tambah_pengguna').disabled = true; </script>";
        echo "<script>   document.getElementById('edit_pengguna').disabled = true; </script>";
    } else {
        $query = mysqli_query($kon, "SELECT username FROM pengguna where username='$username'");
        $jumlah = mysqli_num_rows($query);
        //Kondisi jika username sudah digunakan
        if ($jumlah>0){
            echo "<p class='text-danger'>Username sudah digunakan</p>";
            echo "<script>   document.getElementById('tambah_pengguna').disabled = true; </script>";
            echo "<script>   document.getElementById('edit_pengguna').disabled = true; </script>";
            
        }else {
            //Kondisi jika tersedia
            echo "<p class='text-success'>Username tersedia</p>";
            echo "<script>   document.getElementById('tambah_pengguna').disabled = false; </script>";
            echo "<script>   document.getElementById('edit_pengguna').disabled = false; </script>";
            
        }
    }
    

?>
