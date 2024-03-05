<?php
    //Koneksi database
    include '../../../../config/database.php';

    $query = mysqli_query($kon, "select nama_aplikasi from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);
    //Mengambil tanggal
    $tanggal='';
    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $tanggal=date("d/m/Y",strtotime($_GET["dari_tanggal"]));
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $tanggal= "".date("d/m/Y",strtotime($_GET["dari_tanggal"]))." - ".date("d/m/Y",strtotime($_GET["sampai_tanggal"]))."";

    //Membuat file format excel
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=LAPORAN PENJUALAN PER PRODUK ".strtoupper($row['nama_aplikasi'])." ".$tanggal.".xls");
?>  
<h2><center>LAPORAN PENJUALAN PER PRODUK <?php echo strtoupper($row['nama_aplikasi']);?></center></h2>
<h4>Tanggal : <?php echo $tanggal; ?></h4>

<table border="1">
    <thead>
    <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Kategori</th>
        <th>Produk</th>
        <th>QTY</th>
        <th>Modal</th>
        <th>Jual</th>
        <th>Laba</th>

    </tr>
    </thead>
    <tbody>
    <?php
        //Koneksi database
    

        //Kondisi untuk menampilkan data berdasarkan rentan tanggal yang dipilih
        $kondisi="";
        if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal)='".$_GET['dari_tanggal']."' ";
        if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal) between '".$_GET['dari_tanggal']."' and '".$_GET['sampai_tanggal']."'";
        
        $sql="select k.nama_kt_produk, p.kode_produk,p.nama_produk,sum(d.qty)as qty,sum(d.qty*p.harga_beli) as modal 
        ,sum(d.harga*d.qty)as jual from detail_penjualan d left join produk p on p.kode_produk=d.kode_produk
         left join kategori_produk k on p.kategori_produk=k.id_kt_produk
         left join penjualan on penjualan.no_invoice=d.no_invoice $kondisi
        group by p.nama_produk order by nama_kt_produk asc";
    
        // perintah sql
        $hasil=mysqli_query($kon,$sql);
        $no=0;
        $total_modal=0;
        $total_jual=0;
        $total_laba=0;
        //Menampilkan data dengan perulangan while
        while ($data = mysqli_fetch_array($hasil)):
        $no++;

        $total_modal+=$data['modal'];
        $total_jual+=$data['jual'];
        $total_laba+=$data['jual']-$data['modal'];
    ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $data['kode_produk']; ?></td>
            <td><?php echo $data['nama_kt_produk']; ?></td>
            <td><?php echo $data['nama_produk']; ?></td>
            <td><?php echo $data['qty'];?></td>
            <td>Rp. <?php echo number_format($data['modal'],0,',','.'); ?></td>
            <td>Rp. <?php echo number_format($data['jual'],0,',','.'); ?></td>
            <td>Rp. <?php echo number_format($data['jual']-$data['modal'],0,',','.'); ?></td>
        </tr>
        <!-- bagian akhir (penutup) while -->
        <?php endwhile; ?>
        <tr><td colspan="5"><strong>Total</strong></td><td><strong>Rp. <?php echo number_format($total_modal,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_jual,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_laba,0,',','.'); ?></strong></td></tr>
    </tbody>
</table>
