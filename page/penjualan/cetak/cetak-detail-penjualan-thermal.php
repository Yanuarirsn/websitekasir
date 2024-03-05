<?php
include '../../../config/database.php';
$query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");
$row = mysqli_fetch_array($query);

$no_invoice = $_GET['no_invoice'];
$query = mysqli_query($kon, "SELECT * from penjualan left join pelanggan on penjualan.kode_pelanggan=pelanggan.kode_pelanggan inner join pengguna on penjualan.id_kasir=pengguna.id_pengguna where penjualan.no_invoice='$no_invoice'");
$data = mysqli_fetch_array($query);

$no_invoice = $data['no_invoice'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Print Receipt</title>
    <style>
        * {
            font-size: 12px;
            font-family: 'Times New Roman';
        }

        thead {
            background: #eee;
        }


        td,
        th,
        tr,
        table {
            border-top: 1px solid #6060601f;
            border-collapse: collapse;
            width: 350px;

        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .info {
            padding: 7px 2px 7px 2px;
            text-align: center;
        }

        .detail-invoice {
            padding-left: 7px;
            padding-right: 7px;
        }

        .ticket {
            width: 350px;
            max-width: 350px;
        }

        img {
            max-width: 80px;
            width: 80px;
        }

        .legal-copy {
            padding-top: 10px;
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }
        }
    </style>
</head>

<body onload="window.print();">
    <div class="ticket">
        <p class="centered">
            <img src="../../../page/aplikasi/logo/<?php echo $row['logo']; ?>" alt="brand" />
            <br><strong><?php echo strtoupper($row['nama_aplikasi']); ?></strong>
            <br>
        <div class="info">
            <?php echo $row['alamat']; ?>
            <br><?php echo ', Telp ' . $row['no_telp']; ?>
        </div>
        </p>
        <div class="detail-invoice">
            <p>No Invoice : <?php echo $data['no_invoice']; ?><br>
                Tgl : <?php echo date('d/m/Y', strtotime($data["tanggal"])); ?>, <?php echo date('H:i', strtotime($data["tanggal"])); ?> WIB
                <br>Kasir: <?php echo $data['nama_pengguna']; ?>
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // perintah sql untuk menampilkan daftar penjualan yang berelasi dengan tabel kategori penjualan
                $sql1 = "select * from detail_penjualan inner join produk on produk.kode_produk=detail_penjualan.kode_produk INNER JOIN penjualan on penjualan.no_invoice=detail_penjualan.no_invoice where detail_penjualan.no_invoice='$no_invoice'";
                $result = mysqli_query($kon, $sql1);
                $total = 0;
                $bayar = 0;
                $kembali = 0;
                //Menampilkan data dengan perulangan while
                while ($ambil = mysqli_fetch_array($result)) :
                    $tot = $ambil['harga_jual'] * $ambil['qty'];
                    $total += $tot;
                    $bayar = $ambil['bayar'];
                    $kembali = $ambil['kembali'];
                ?>
                    <tr>
                        <td><?php echo $ambil['nama_produk']; ?></td>
                        <td style="text-align: center;"><?php echo $ambil['qty']; ?></td>
                        <td style="text-align:right">Rp. <?php echo number_format($ambil['harga'], 0, ',', '.'); ?></td>
                        <td style="text-align:right">Rp. <?php echo number_format($tot, 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr style="text-align:right">
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>Rp. <?php echo number_format($total, 0, ',', '.');  ?></strong></td>
                </tr>
                <tr style="text-align:right">
                    <td colspan="3"><strong>Bayar</strong></td>
                    <td><strong>Rp. <?php echo number_format($bayar, 0, ',', '.');  ?></strong></td>
                </tr>
                <tr style="text-align:right">
                    <td colspan="3"><strong>Kembali</strong></td>
                    <td><strong>Rp. <?php echo number_format($kembali, 0, ',', '.');  ?></strong></td>
                </tr>
            </tbody>
        </table>
        <div class="legal-copy">
            <p class="centered"><strong> Terima Kasih Telah Berbelanja di Toko Kami </strong><br>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>

    </div>
</body>

</html>