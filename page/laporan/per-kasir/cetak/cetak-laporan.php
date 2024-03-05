<!DOCTYPE html>
<html>
<head>
  <!-- Custom styles for this template -->
  <link href="../../../../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
    <body onload="window.print();">
        <?php
        include '../../../../config/database.php';
   
        $query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");    
        $row = mysqli_fetch_array($query);
        ?>
        <div class="container-fluid">
            <div class="card">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-sm-2 float-left">
                    <img src="../../../../page/aplikasi/logo/<?php echo $row['logo']; ?>" width="95px" alt="brand"/>
                    </div>
                    <div class="col-sm-10 float-left">
                        <h3><?php echo strtoupper($row['nama_aplikasi']);?></h3>
                        <h6><?php echo $row['alamat'].', Telp '.$row['no_telp'];?></h6>
                        <h6><?php echo $row['website'];?></h6>
                    </div>
                </div>
            </div>
                <div class="card-body">
                    <!--rows -->
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>No</th>
                                    <th>Kode Kasir</th>
                                    <th>Nama</th>
                                    <th>Item Terjual</th>
                                    <th>QTY Terjual</th>
                                    <th>Total Pendapatan</th>
                                    <th>Laba</th>
                                
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        $kondisi="";

                                        if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal)='".$_GET['dari_tanggal']."' ";
                                        if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal) between '".$_GET['dari_tanggal']."' and '".$_GET['sampai_tanggal']."'";
                                        
                                        $sql="select k.kode_pengguna as kode_kasir, k.nama_pengguna as kasir, count(*) as item_terjual, sum(d.qty) as qty_terjual, sum(d.qty*pr.harga_beli) as modal, sum(d.qty*d.harga) as pendapatan, sum((d.qty*d.harga)-(d.qty*pr.harga_beli)) as laba from penjualan p inner join pengguna k on k.id_pengguna=p.id_kasir inner join detail_penjualan d on d.no_invoice=p.no_invoice inner join produk pr on pr.kode_produk=d.kode_produk $kondisi group by k.nama_pengguna order by kode_kasir";
                                    
                                        // perintah sql
                                        $hasil=mysqli_query($kon,$sql);
                                        $no=0;
                                        $total_item=0;
                                        $total_qty_terjual=0;
                                        $total_pendapatan=0;
                                        $total_laba=0;
                                        //Menampilkan data dengan perulangan while
                                        while ($data = mysqli_fetch_array($hasil)):
                                        $no++;
                                        $total_item+=$data['item_terjual'];
                                        $total_qty_terjual+=$data['qty_terjual'];
                                        $total_pendapatan+=$data['pendapatan'];
                                        $total_laba+=$data['laba'];

                                    
                                    ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['kode_kasir']; ?></td>
                                        <td><?php echo $data['kasir']; ?></td>
                                        <td><?php echo $data['item_terjual']; ?></td>
                                        <td><?php echo $data['qty_terjual'];?></td>
                                        <td>Rp. <?php echo number_format($data['pendapatan'],0,',','.'); ?></td>
                                        <td>Rp. <?php echo number_format($data['laba'],0,',','.'); ?></td>
                                    </tr>
                                    <!-- bagian akhir (penutup) while -->
                                    <?php endwhile; ?>
                                    <tr><td colspan="3"><strong>Total</strong></td><td><strong><?php echo number_format($total_item,0,',','.'); ?></strong></td><td><strong><?php echo number_format($total_qty_terjual,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_pendapatan,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_laba,0,',','.'); ?></strong></td> </tr>
                            
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>