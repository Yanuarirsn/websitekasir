<?php
    //Memanggil plugin FPDF
    require('../../../../assets/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('P', 'mm','Letter');

    //Koneksi database
    include '../../../../config/database.php';

    //Mengambil data profil aplikasi
    $query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);

    //Membuat Header page
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
    //Kondisi untuk menampilkan data berdasarkan rentan tanggal yang dipilih
    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal)='".$_GET['dari_tanggal']."' ";
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal) between '".$_GET['dari_tanggal']."' and '".$_GET['sampai_tanggal']."'";
    
    $no=1;
    $total=0;
    $total_modal=0;
    $total_jual=0;
    $total_laba=0;

    //Eksekusi perintah SQL
    $hasil = mysqli_query($kon, "select k.nama_kt_produk, p.kode_produk,p.nama_produk,sum(d.qty)as qty,sum(d.qty*p.harga_beli) as modal ,sum(d.harga*d.qty)as jual from detail_penjualan d left join produk p on p.kode_produk=d.kode_produk left join kategori_produk k on p.kategori_produk=k.id_kt_produk  left join penjualan on penjualan.no_invoice=d.no_invoice $kondisi group by p.nama_produk  order by nama_kt_produk asc");
    while ($data = mysqli_fetch_array($hasil)){
        $qty= $data['qty'];
        $modal=$data['modal'];
        $jual=$data['jual'];
        $laba=$jual-$modal;
        $total_modal+=$modal;
        $total_jual+=$jual;
        $total_laba+=$laba;
    
        $pdf->Cell(8,6,$no,1,0);
        $pdf->Cell(15,6,$data['kode_produk'],1,0);
        $pdf->Cell(40,6,$data['nama_kt_produk'],1,0);
        $pdf->Cell(50,6, substr($data['nama_produk'], 0, 28),1,0);
        $pdf->Cell(11,6,$qty,1,0,'C');
        $pdf->Cell(25,6,'Rp. '.number_format($modal,0,',','.'),1,0);
        $pdf->Cell(25,6,'Rp. '.number_format($jual,0,',','.'),1,0);
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