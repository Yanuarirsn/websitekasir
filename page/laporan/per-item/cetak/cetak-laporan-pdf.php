<?php
    require('../../../../assets/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('P', 'mm','Letter');
    
    include '../../../../config/database.php';
    $query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);

    $pdf->AddPage();
    $pdf->Image('../../../../page/aplikasi/logo/'.$row['logo'],15,5,30,30);
    $pdf->SetFont('Arial','B',21);
    $pdf->Cell(0,7,strtoupper($row['nama_aplikasi']),0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,7,$row['alamat'].', Telp '.$row['no_telp'],0,1,'C');
    $pdf->Cell(0,7,$row['website'],0,1,'C');
    $pdf->Cell(10,7,'',0,1);
    $tanggal='';
    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])){
        $tanggal=date("d/m/Y",strtotime($_GET["dari_tanggal"]));
    }
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])){
        $tanggal=date("d/m/Y",strtotime($_GET["dari_tanggal"]))." - ".date("d/m/Y",strtotime($_GET["sampai_tanggal"]));
    }

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,6,'Laporan Penjualan Tanggal: ',0,0);
    $pdf->Cell(30,6,$tanggal,0,1);

    $pdf->Cell(10,3,'',0,1);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(8,6,'No',1,0,'C');
    $pdf->Cell(15,6,'Kode',1,0,'C');
    $pdf->Cell(40,6,'Kategori',1,0,'C');
    $pdf->Cell(50,6,'Produk',1,0,'C');
    $pdf->Cell(11,6,'Qty',1,0,'C');
    $pdf->Cell(25,6,'Modal',1,0,'C');
    $pdf->Cell(25,6,'Jual',1,0,'C');
    $pdf->Cell(22,6,'Laba',1,1,'C');

    
    $pdf->SetFont('Arial','',10);

    $kondisi="";

    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal)='".$_GET['dari_tanggal']."' ";
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal) between '".$_GET['dari_tanggal']."' and '".$_GET['sampai_tanggal']."'";
    
    $no=1;
    $total=0;
    $total_modal=0;
    $total_jual=0;
    $total_laba=0;
    //Query untuk mengambil data mahasiswa pada tabel mahasiswa
    $hasil = mysqli_query($kon, "select * from detail_penjualan inner join produk on produk.kode_produk=detail_penjualan.kode_produk LEFT JOIN kategori_produk on produk.kategori_produk=kategori_produk.id_kt_produk INNER JOIN penjualan on penjualan.no_invoice=detail_penjualan.no_invoice $kondisi order by nama_kt_produk asc");
    while ($data = mysqli_fetch_array($hasil)){
        $qty= $data['qty'];
        $harga_beli=$data['harga_beli']*$qty;
        $harga_jual=$data['harga']*$qty;
        $laba=$harga_jual-$harga_beli;

        $total_modal+=$harga_beli;
        $total_jual+=$harga_jual;
        $total_laba+=$laba;
     
     

        $pdf->Cell(8,6,$no,1,0);
        $pdf->Cell(15,6,$data['kode_produk'],1,0);
        $pdf->Cell(40,6,$data['nama_kt_produk'],1,0);
        $pdf->Cell(50,6, substr($data['nama_produk'], 0, 28),1,0);
        $pdf->Cell(11,6,$qty,1,0,'C');
        $pdf->Cell(25,6,'Rp. '.number_format($harga_beli,0,',','.'),1,0);
        $pdf->Cell(25,6,'Rp. '.number_format($harga_jual,0,',','.'),1,0);
        $pdf->Cell(22,6,'Rp. '.number_format($laba,0,',','.'),1,1);
        $no++;
    }
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(124,6,'Total',1,0,'C');
    $pdf->Cell(25,6,'Rp.'.number_format($total_modal,0,',','.'),1,0);
    $pdf->Cell(25,6,'Rp.'.number_format($total_jual,0,',','.'),1,0);
    $pdf->Cell(22,6,'Rp.'.number_format($total_laba,0,',','.'),1,1);
   
    $pdf->Output();
?>