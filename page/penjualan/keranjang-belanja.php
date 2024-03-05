<?php
//Memulai session
session_start();
//Koneksi database
include '../../config/database.php';
$kode_produk = "";
$stok_produk = 0;
$jumlah_beli = 0;
$aksi = "";
//Menerima data yang dikirim dengan ajax
if (isset($_POST['kode_produk'])) {
    $kode_produk = $_POST['kode_produk'];
}
if (isset($_POST['stok_produk'])) {
    $stok_produk = $_POST['stok_produk'];
}
if (isset($_POST['jumlah_beli'])) {
    $jumlah_beli = $_POST['jumlah_beli'];
}
if (isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];
}

//Mengambil data produk
$query = mysqli_query($kon, "SELECT * FROM produk where kode_produk='$kode_produk'");
$productByCode = mysqli_fetch_array($query);


//Memecah data produk ke dalam array
if (isset($_POST['aksi'])) {
    $itemArray = array($productByCode['kode_produk'] => array('jumlah_beli' => $jumlah_beli, 'id_produk' => $productByCode['id_produk'], 'kode_produk' => $productByCode['kode_produk'], 'nama_produk' => $productByCode['nama_produk'], 'harga_jual' => $productByCode['harga_jual']));
}
switch ($aksi) {
        //Menambah produk ke kerangjang belanja
    case "tambahkan_ke_keranjang":
        if (!empty($_SESSION["cart_item"])) {
            if (in_array($productByCode['kode_produk'], array_keys($_SESSION["cart_item"]))) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($productByCode['kode_produk'] == $k) {
                        if (empty($_SESSION["cart_item"][$k]["jumlah_beli"])) {
                            $_SESSION["cart_item"][$k]["jumlah_beli"] = 0;
                        }
                        if ($_SESSION["cart_item"][$k]["jumlah_beli"] + $jumlah_beli <= $stok_produk) {
                            $_SESSION["cart_item"][$k]["jumlah_beli"] += $jumlah_beli;
                        } else {
                            echo "<script> alert('Jumlah beli tidak boleh melebihi stok produk');</script>";
                            $_SESSION["cart_item"][$k]["jumlah_beli"];
                        }
                    }
                }
            } else {
                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
            }
        } else {
            $_SESSION["cart_item"] = $itemArray;
        }
        break;
    case "hapus_item":
        //Menghapus produk dari keranjang belanja
        if (!empty($_SESSION["cart_item"])) {
            foreach ($_SESSION["cart_item"] as $k => $v) {
                if ($_POST["kode_produk"] == $k)
                    unset($_SESSION["cart_item"][$k]);
                if (empty($_SESSION["cart_item"]))
                    unset($_SESSION["cart_item"]);
            }
        }
        break;
}
?>

<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>

            <th><strong>Kode</strong></th>
            <th><strong>Produk</strong></th>
            <th width='15%'><strong>Harga</strong></th>
            <th width='13%'><strong>Qty</strong></th>
            <th width='20%'><strong>Total</strong></th>
            <th><strong>Opsi</strong></th>
        </tr>
    </thead>
    <tbody>

        <?php
        $tot = 0;
        if (!empty($_SESSION["cart_item"])) {
            foreach ($_SESSION["cart_item"] as $item) {
                $harga = $item["harga_jual"];
                $qty = $item["jumlah_beli"];
                $sub_tot = $item["jumlah_beli"] * $item["harga_jual"];

                $tot += $sub_tot;
        ?>
                <input type="hidden" name="produk_dibeli[]" value="<?php echo $item["kode_produk"]; ?>" />
                <input type="hidden" name="qty[]" value="<?php echo $qty; ?>" />
                <input type="hidden" name="harga[]" value="<?php echo $harga; ?>" />
                <tr>
                    <td><?php echo $item["kode_produk"]; ?></td>
                    <td><?php echo $item["nama_produk"]; ?></td>
                    <td><?php echo number_format($harga, 0, ',', '.'); ?></td>
                    <td> <?php echo $qty; ?> </td>
                    <td><?php echo number_format($sub_tot, 0, ',', '.'); ?></td>
                    <td> <button type="button" onClick="hapus_item('hapus_item','<?php echo $item["kode_produk"]; ?>')" id="batal_pembelian" class="btn-hapus btn btn-danger btn-circle"><i class="fas fa-trash"></i></button></td>
                </tr>
        <?php }
        } ?>
        <tr>
            <td colspan="4" style="text-align:right">
                <h4>Total</h4>
            </td>
            <td colspan="2">
                <h4>Rp. <?php echo number_format($tot, 0, ',', '.'); ?></h4>
            </td>
        </tr>

    </tbody>
</table>

<div class="row">
    <div class="col-sm-7">
        <div class="form-group">
            <input name="total_bayar" id="total_bayar" value="<?php echo $tot; ?>" type="hidden" class="form-control">
        </div>
        <div class="form-group">
            <label>Bayar:</label>
            <input name="bayar" id="bayar" type="text" class="form-control" placeholder="Masukkan Uang Bayar">
        </div>
        <div class="form-group">
            <div id="nominal_bayar" class='font-weight-bold'></div>
        </div>
        <div class="form-group">
            <label>Kembali:</label>
            <input type="text" id="tampil_kembali" class="form-control" disabled>
            <input type="hidden" name="kembali" id="kembali" class="form-control">
        </div>
        <div class="form-group">
            <button type="submit" id="buat_transaksi" name="buat_transaksi" class="btn btn-success btn-block" disabled><span class="text">Buat Transaksi</span></button>
        </div>
    </div>
</div>



<script>
    //Membuat format rupiah
    function format_rupiah(nominal) {
        var reverse = nominal.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        return ribuan = ribuan.join('.').split('').reverse().join('');
    }

    //Menghitung kembalian dan mengaktifkan tombol buat transaksi
    $("#bayar").bind('keyup', function() {
        var total_bayar = $('#total_bayar').val();
        var bayar = $('#bayar').val();

        var kembali = bayar - total_bayar;

        if (kembali >= 0 && total_bayar != 0) {
            $('#kembali').val(kembali);
            $('#tampil_kembali').val('Rp. ' + format_rupiah(kembali));
            document.getElementById("buat_transaksi").disabled = false;
        } else {
            $('#kembali').val(0);
            document.getElementById("buat_transaksi").disabled = true;
        }

    });

    //Fungsi menghapus produk dari keranjang belanja
    function hapus_item(aksi, kode_produk) {
        var jumlah_beli = 0;
        $.ajax({
            url: 'page/penjualan/keranjang-belanja.php',
            method: 'POST',
            data: {
                kode_produk: kode_produk,
                aksi: aksi,
                jumlah_beli: jumlah_beli
            },
            success: function(data) {
                $('#tampil_cart').html(data);
            }
        });
    }

    //Membuat info nominal bayar
    $('#bayar').bind('keyup', function() {
        var bayar = $("#bayar").val();
        $("#nominal_bayar").text('Rp.' + format_rupiah(bayar));
    });
</script>