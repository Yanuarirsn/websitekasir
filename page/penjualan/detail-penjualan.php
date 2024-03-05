<?php

include 'config/database.php';
if (isset($_GET['id_penjualan'])) {
    $id_penjualan = $_GET['id_penjualan'];
    $query = mysqli_query($kon, "SELECT * from penjualan left join pelanggan on penjualan.kode_pelanggan=pelanggan.kode_pelanggan left join pengguna on pengguna.id_pengguna=penjualan.id_kasir where id_penjualan='$id_penjualan'");
} else if (isset($_GET['no_invoice'])) {
    $no_invoice = $_GET['no_invoice'];
    $query = mysqli_query($kon, "SELECT * from penjualan left join pelanggan on penjualan.kode_pelanggan=pelanggan.kode_pelanggan left join pengguna on pengguna.id_pengguna=penjualan.id_kasir where no_invoice='$no_invoice'");
}

$data = mysqli_fetch_array($query);
$no_invoice = $data['no_invoice'];



?>

<div class="container-fluid">
    <!--Bagian heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail Penjualan</h1>
    <p class="mb-4">Halaman detail penjualan berisi informasi seluruh penjualan yang dapat di kelolah oleh admin.</p>
    <a href="index.php?page=data_penjualan"><button class="btn btn-primary"><i class="fa fa-angle-left"></i> Kembali</button></a>
    <br /><br />
    <?php
    //Validasi untuk menampilkan pesan pemberitahuan saat user menambah penjualan
    if (isset($_GET['add'])) {
        if ($_GET['add'] == 'berhasil') {
            echo "<div class='alert alert-success'>Transaksi Berhasil</div>";
        } else if ($_GET['add'] == 'gagal') {
            echo "<div class='alert alert-danger'><strong>Gagal!</strong> Transaksi gagal!</div>";
        }
    }

    ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">

        </div>
        <div class="card-body">
            <!--rows -->
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>No Invoice</td>
                                    <td>: <?php echo $data['no_invoice']; ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Transaksi</td>
                                    <td>: <?php echo date('d/m/Y', strtotime($data["tanggal"])); ?></td>
                                </tr>
                                <tr>
                                    <td>Jam</td>
                                    <td>: <?php echo date('H:i', strtotime($data["tanggal"])); ?> WIB</td>
                                </tr>
                                <tr>
                                    <td>Kasir</td>
                                    <td>: <?php echo $data['nama_pengguna']; ?></td>
                                </tr>
                                <tr>
                                    <td>Pelanggan</td>
                                    <td>: <?php echo $data['nama_pelanggan']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-8">

                </div>
            </div>
            <!--rows -->
            <div>
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>QTY</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            // perintah sql untuk menampilkan daftar penjualan
                            $sql1 = "select * from detail_penjualan inner join produk on produk.kode_produk=detail_penjualan.kode_produk INNER JOIN penjualan on penjualan.no_invoice=detail_penjualan.no_invoice where detail_penjualan.no_invoice='$no_invoice'";
                            $result = mysqli_query($kon, $sql1);
                            $no = 0;
                            $total = 0;
                            $bayar = 0;
                            $kembali = 0;
                            //Menampilkan data dengan perulangan while
                            while ($ambil = mysqli_fetch_array($result)) :
                                $no++;
                                $tot = $ambil['harga_jual'] * $ambil['qty'];
                                $total += $tot;
                                $bayar = $ambil['bayar'];
                                $kembali = $ambil['kembali'];
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $ambil['kode_produk']; ?></td>
                                    <td><?php echo $ambil['nama_produk']; ?></td>
                                    <td>Rp. <?php echo number_format($ambil['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo $ambil['qty']; ?></td>
                                    <td>Rp. <?php echo number_format($tot, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <tr>
                                <td colspan="5" style="text-align:right"><strong>Total</strong></td>
                                <td><strong>Rp. <?php echo number_format($total, 0, ',', '.');  ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align:right"><strong>Bayar</strong></td>
                                <td><strong>Rp. <?php echo number_format($bayar, 0, ',', '.');  ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align:right"><strong>Kembali</strong></td>
                                <td><strong>Rp. <?php echo number_format($kembali, 0, ',', '.');  ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Tombol cetak invoice -->
                <a href="page/penjualan/cetak/cetak-detail-penjualan.php?no_invoice=<?php echo $data['no_invoice']; ?>" target='blank' class="btn btn-primary btn-icon-split"><span class="text"><i class="fas fa-print"></i> Cetak Invoice</span></a>
                <a href="page/penjualan/cetak/cetak-detail-penjualan-thermal.php?no_invoice=<?php echo $data['no_invoice']; ?>" target='blank' class="btn btn-success btn-icon-split"><span class="text"><i class="fas fa-print"></i> Print Thermal</span></a>
                <a href="page/penjualan/cetak/cetak-detail-penjualan-pdf.php?no_invoice=<?php echo $data['no_invoice']; ?>" target='blank' class="btn btn-danger btn-icon-pdf"><span class="text"><i class="fas fa-file-pdf"></i> Export PDF</span></a>
            </div>
            <!--rows -->
        </div>
    </div>
</div>