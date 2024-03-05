<?php
    //Koneksi database
    include '../../../../config/database.php';
    //Mengambil nama aplikasi
    $query = mysqli_query($kon, "select nama_aplikasi from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);

    //Mengambil tanggal
    $tanggal='';
    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $tanggal=date("d/m/Y",strtotime($_GET["dari_tanggal"]));
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $tanggal= "".date("d/m/Y",strtotime($_GET["dari_tanggal"]))." - ".date("d/m/Y",strtotime($_GET["sampai_tanggal"]))."";
    
    //Membuat file format excel
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=LAPORAN PENJUALAN PER ITEM ".strtoupper($row['nama_aplikasi'])." ".$tanggal.".xls");
?>  
<h2><center>LAPORAN PENJUALAN PER ITEM <?php echo strtoupper($row['nama_aplikasi']);?></center></h2>
<h4>Tanggal : <?php echo $tanggal; ?></h4>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode</th>
            <th>Kategori</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Modal</th>
            <th>Jual</th>
            <th>Laba</th>

        </tr>
    </thead>
    <tbody>
    <?php

        $kondisi="";

        if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal)='".$_GET['dari_tanggal']."' ";
        if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal) between '".$_GET['dari_tanggal']."' and '".$_GET['sampai_tanggal']."'";
        
        $sql="select * from detail_penjualan d left join produk p on p.kode_produk=d.kode_produk LEFT JOIN kategori_produk k on p.kategori_produk=k.id_kt_produk left join penjualan on penjualan.no_invoice=d.no_invoice $kondisi group by d.kode_produk, d.qty, d.no_invoice order by tanggal desc";
    
        // perintah sql
        $hasil=mysqli_query($kon,$sql);
        $no=0;
        $total_modal=0;
        $total_jual=0;
        $total_laba=0;

        //Menampilkan data dengan perulangan while
        while ($data = mysqli_fetch_array($hasil)):
        $no++;

        $qty= $data['qty'];
        $harga_beli=$data['harga_beli']*$qty;
        $harga_jual=$data['harga']*$qty;
        $laba=$harga_jual-$harga_beli;

        $total_modal+=$harga_beli;
        $total_jual+=$harga_jual;
        $total_laba+=$laba;
    ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo date('d-m-Y', strtotime($data["tanggal"]));?></td>
            <td><?php echo $data['kode_produk']; ?></td>
            <td><?php echo $data['nama_kt_produk']; ?></td>
            <td><?php echo $data['nama_produk']; ?></td>
            <td><?php echo $qty;?></td>
            <td>Rp. <?php echo number_format($harga_beli,0,',','.'); ?></td>
            <td>Rp. <?php echo number_format($harga_jual,0,',','.'); ?></td>
            <td>Rp. <?php echo number_format($laba,0,',','.'); ?></td>
        </tr>
        <!-- bagian akhir (penutup) while -->
        <?php endwhile; ?>
        <tr><td colspan="6"><strong>Total</strong></td><td><strong>Rp. <?php echo number_format($total_modal,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_jual,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_laba,0,',','.'); ?></strong></td></tr>
    </tbody>
</table>
