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
                                        <td><?php echo date('d/m/Y', strtotime($data["tanggal"]));?></td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>