<?php
$tanggal = date('l, d-m-Y');
date_default_timezone_set("Asia/Jakarta");
$jam = date("H:i");

//Koneksi database
include '../../../config/database.php';
//Mengambil nama aplikasi
$query = mysqli_query($kon, "select nama_aplikasi from profil_aplikasi order by nama_aplikasi desc limit 1");
$row = mysqli_fetch_array($query);

//Membuat file format excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=LAPORAN EXCEL DATA PELANGGAN " . strtoupper($row['nama_aplikasi']) . ".xls");
?>
<h2>
    <center>LAPORAN EXCEL DATA PELANGGAN <?php echo strtoupper($row['nama_aplikasi']); ?></center>
</h2>
<p align="right"><?php echo "$tanggal $jam"; ?></p>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Pelanggan</th>
            <th>Telp</th>
            <th>Alamat</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Lahir</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $query_mysqli = mysqli_query($kon, "select * from pelanggan order by id_pelanggan desc") or die(mysqli_error());
        // perintah sql
        $no = 0;
        //Menampilkan data dengan perulangan while
        while ($data = mysqli_fetch_array($query_mysqli)) :
            $no++;
        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $data['kode_pelanggan']; ?></td>
                <td><?php echo $data['nama_pelanggan']; ?></td>
                <td><?php echo $data['no_telp']; ?></td>
                <td><?php echo $data['alamat_pelanggan']; ?></td>
                <td><?php echo $data['jenis_kelamin'] == 1 ? 'Laki-laki' : 'Perempuan'; ?></td>
                <td><?php echo date('d/m/Y', strtotime($data["tanggal_lahir"])); ?></td>
                <td><?php echo $data['status'] == 1 ? 'Aktif' : 'Tidak Aktif'; ?></td>
            </tr>
            <!-- bagian akhir (penutup) while -->
        <?php endwhile; ?>
    </tbody>
</table>