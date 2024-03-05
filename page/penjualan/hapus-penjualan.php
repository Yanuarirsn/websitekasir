
<form action="page/penjualan/hapus-penjualan.php" method="post">
        <!-- rows -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                     <h5>Apakah anda yakin ingin menghapus data penjualan  No Invoice #<?php  echo $_POST["no_invoice"];?> ? </h5>
                     <h5>Stok produk akan di kembalikan</h5>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_penjualan" value="<?php echo $_POST["id_penjualan"]; ?>"/>
        <input type="hidden" name="no_invoice" value="<?php echo $_POST["no_invoice"]; ?>"/>
        <button type="submit" name="simpan_hapus" class="btn btn-primary">Hapus</button>
</form>

<?php
      if (isset($_POST['simpan_hapus'])) {
        //Memulai session
        session_start();
        //Inlude database
        include '../../config/database.php';
        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");
       
        //Filter form inputan
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        //Menerima nilai yang dikirim dari form
        $id_penjualan=input($_POST["id_penjualan"]);
        $no_invoice=input($_POST["no_invoice"]);

        //mengambil data produk dan qty berdasarkan no invoice
        $ambil_produk= mysqli_query($kon, "SELECT kode_produk,qty FROM detail_penjualan where no_invoice='$no_invoice'");
        while ($data = mysqli_fetch_array($ambil_produk)):
        $kode_produk=$data['kode_produk'];
        $qty=$data['qty'];
        //kembalikan stok produk
        $update_stok=mysqli_query($kon,"update produk set stok_produk=stok_produk+$qty where kode_produk='$kode_produk'");
        endwhile;
      
        //Menghapus di tabel penjualan
        $hapus_penjualan=mysqli_query($kon,"delete from penjualan where id_penjualan=$id_penjualan");

        //Menghapus di tabel detail_penjualan
        $hapus_detail_penjualan=mysqli_query($kon,"delete from detail_penjualan where no_invoice='$no_invoice'");


        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($update_stok && $hapus_penjualan && $hapus_detail_penjualan) {
            //Jika berhasil lakukan commit
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=data_penjualan&hapus=berhasil");
        }
        else {
            //Jika gagal lakukan rollback
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../index.php?page=data_penjualan&hapus=gagal");

        }

    }
?>