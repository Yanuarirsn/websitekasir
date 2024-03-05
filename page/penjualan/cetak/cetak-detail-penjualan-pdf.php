<?php
    require('../../../assets/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('P', 'mm','Letter');

    include '../../../config/database.php';
    $query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);

    $pdf->AddPage();
    $pdf->Image('../../../page/aplikasi/logo/'.$row['logo'],15,5,30,30);
    $pdf->SetFont('Arial','B',21);
    $pdf->Cell(0,7,strtoupper($row['nama_aplikasi']),0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,7,$row['alamat'].', Telp '.$row['no_telp'],0,1,'C');
    $pdf->Cell(0,7,$row['website'],0,1,'C');

 

    $no_invoice=$_GET['no_invoice'];
    $query = mysqli_query($kon, "SELECT * from penjualan left join pelanggan on penjualan.kode_pelanggan=pelanggan.kode_pelanggan inner join pengguna on penjualan.id_kasir=pengguna.id_pengguna where penjualan.no_invoice='$no_invoice'");
    $data = mysqli_fetch_array($query);

    $no_invoice=$data['no_invoice'];


    $pdf->Cell(10,7,'',0,1);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,6,'No Invoice',0,0);
    $pdf->Cell(20,6,': '.$data['no_invoice'],0,1);

    $pdf->Cell(50,6,'Tanggal Transaksi',0,0);
    $pdf->Cell(20,6,': '.date("d/m/Y",strtotime($data['tanggal'])),0,1);

    $pdf->Cell(50,6,'Jam',0,0);
    $pdf->Cell(20,6,': '.date("H:i",strtotime($data['tanggal'])).' WIB',0,1);

    $pdf->Cell(50,6,'Kasir',0,0);
    $pdf->Cell(20,6,': '.$data['nama_pengguna'],0,1);

    $pdf->Cell(50,6,'Pelanggan',0,0);
    $pdf->Cell(20,6,': '.$data['nama_pelanggan'],0,1);

    $pdf->Cell(10,7,'',0,1);

    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(8,6,'No',1,0,'C');
    $pdf->Cell(20,6,'Kode',1,0,'C');
    $pdf->Cell(80,6,'Produk',1,0,'C');
    $pdf->Cell(30,6,'Harga',1,0,'C');
    $pdf->Cell(20,6,'Qty',1,0,'C');
    $pdf->Cell(30,6,'Sub Total',1,1,'C');

    $pdf->SetFont('Arial','',10);

    $no=1;
    $total=0;
    //Query untuk mengambil data mahasiswa pada tabel mahasiswa
    $hasil = mysqli_query($kon, "select * from detail_penjualan inner join produk on produk.kode_produk=detail_penjualan.kode_produk INNER JOIN penjualan on penjualan.no_invoice=detail_penjualan.no_invoice where detail_penjualan.no_invoice='$no_invoice'");
    while ($data = mysqli_fetch_array($hasil)){
        $harga=$data['harga'];
        $sub_total=$harga*$data['qty'];
        $bayar=$data['bayar'];
        $kembali=$data['kembali'];
     
        $total+=$sub_total;

        $pdf->Cell(8,6,$no,1,0);
        $pdf->Cell(20,6,$data['kode_produk'],1,0);
        $pdf->Cell(80,6,$data['nama_produk'],1,0);
        $pdf->Cell(30,6,'Rp. '.number_format($harga,0,',','.'),1,0);
        $pdf->Cell(20,6,$data['qty'],1,0,'C');
        $pdf->Cell(30,6,'Rp. '.number_format($sub_total,0,',','.'),1,1);
        $no++;
    }
 
    $pdf->Cell(138);
    $pdf->Cell(20,6,'Total',1,0);
    $pdf->Cell(30,6,'Rp.'.number_format($total,0,',','.'),1,1);
   
    $pdf->Cell(138);
    $pdf->Cell(20,6,'Bayar',1,0);
    $pdf->Cell(30,6,'Rp.'.number_format($bayar,0,',','.'),1,1);

    $pdf->Cell(138);
    $pdf->Cell(20,6,'Kembali',1,0);
    $pdf->Cell(30,6,'Rp.'.number_format($kembali,0,',','.'),1,1);
   
    $pdf->Output();
