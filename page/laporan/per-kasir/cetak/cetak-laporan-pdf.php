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
    $pdf->Cell(40,6,'Nama',1,0,'C');
    $pdf->Cell(30,6,'Produk Terjual',1,0,'C');
    $pdf->Cell(30,6,'Qty',1,0,'C');
    $pdf->Cell(30,6,'Pendapatan',1,0,'C');
    $pdf->Cell(30,6,'Laba',1,1,'C');

    
    $pdf->SetFont('Arial','',10);

    $kondisi="";

    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal)='".$_GET['dari_tanggal']."' ";
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal) between '".$_GET['dari_tanggal']."' and '".$_GET['sampai_tanggal']."'";
    
    $no=1;
    $total=0;
	$total_item=0;
    $total_qty_terjual=0;
    $total_pendapatan=0;
    $total_laba=0;
    //Query untuk mengambil data mahasiswa pada tabel mahasiswa
    $hasil = mysqli_query($kon, "select k.kode_pengguna as kode_kasir, k.nama_pengguna as kasir, count(*) as item_terjual, sum(d.qty) as qty_terjual, sum(d.qty*pr.harga_beli) as modal, sum(d.qty*d.harga) as pendapatan, sum((d.qty*d.harga)-(d.qty*pr.harga_beli)) as laba from penjualan p inner join pengguna k on k.id_pengguna=p.id_kasir inner join detail_penjualan d on d.no_invoice=p.no_invoice inner join produk pr on pr.kode_produk=d.kode_produk $kondisi group by k.nama_pengguna order by kode_kasir");
    while ($data = mysqli_fetch_array($hasil)){
      
        $total_item+=$data['item_terjual'];
        $total_qty_terjual+=$data['qty_terjual'];
        $total_pendapatan+=$data['pendapatan'];
        $total_laba+=$data['laba'];
     

        $pdf->Cell(8,6,$no,1,0);
        $pdf->Cell(15,6,$data['kode_kasir'],1,0,'C');
        $pdf->Cell(40,6,$data['kasir'],1,0);
        $pdf->Cell(30,6,$data['item_terjual'],1,0,'C');
        $pdf->Cell(30,6,$data['qty_terjual'],1,0,'C');
        $pdf->Cell(30,6,'Rp. '.number_format($data['pendapatan'],0,',','.'),1,0,'C');
        $pdf->Cell(30,6,'Rp. '.number_format($data['laba'],0,',','.'),1,1,'C');
        $no++;
    }
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(63,6,'Total',1,0,'C');
    $pdf->Cell(30,6,number_format($total_item,0,',','.'),1,0,'C');
    $pdf->Cell(30,6,number_format($total_qty_terjual,0,',','.'),1,0,'C');
    $pdf->Cell(30,6,'Rp.'.number_format($total_pendapatan,0,',','.'),1,0,'C');
    $pdf->Cell(30,6,'Rp.'.number_format($total_laba,0,',','.'),1,1,'C');
    $pdf->Output();
?>