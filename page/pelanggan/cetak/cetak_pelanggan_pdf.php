<?php
$tanggal = mktime(date('m'), date("d"), date('Y'));
date_default_timezone_set("Asia/Jakarta");
$jam = date("H:i");

require('../../../assets/plugin/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm', 'Letter');

include '../../../config/database.php';
$query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");
$row = mysqli_fetch_array($query);


$pdf->AddPage();
$pdf->Image('../../../page/aplikasi/logo/' . $row['logo'], 15, 5, 30, 30);
$pdf->SetFont('Arial', 'B', 21);
$pdf->Cell(0, 7, strtoupper($row['nama_aplikasi']), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, $row['alamat'] . ', Telp ' . $row['no_telp'], 0, 1, 'C');
$pdf->Cell(0, 7, $row['website'], 0, 1, 'C');
$pdf->Cell(0, 7, ' ' . date("d/m/Y", ($tanggal)) . ' ' . $jam, 0, 1, 'R');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 6, 'Laporan Data Pelanggan ', 0, 1, 'C');

$pdf->Cell(10, 3, '', 0, 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(8, 6, 'No', 1, 0, 'C');
$pdf->Cell(20, 6, 'Kode', 1, 0, 'C');
$pdf->Cell(40, 6, 'Pelanggan', 1, 0, 'C');
$pdf->Cell(30, 6, 'Telp', 1, 0, 'C');
$pdf->Cell(70, 6, 'Alamat', 1, 0, 'C');
$pdf->Cell(30, 6, 'Jenis Kelamin', 1, 0, 'C');
$pdf->Cell(30, 6, 'Tanggal Lahir', 1, 0, 'C');
$pdf->Cell(20, 6, 'Status', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);

$sql = "select * from pelanggan order by id_pelanggan desc";
$hasil = mysqli_query($kon, $sql);

$no = 1;
//Menampilkan data dengan perulangan while
while ($data = mysqli_fetch_array($hasil)) {

    $pdf->Cell(8, 6, $no, 1, 0, 'C');
    $pdf->Cell(20, 6, $data['kode_pelanggan'], 1, 0, 'C');
    $pdf->Cell(40, 6, substr($data['nama_pelanggan'], 0, 28), 1, 0);
    $pdf->Cell(30, 6, $data['no_telp'], 1, 0, 'C');
    $pdf->Cell(70, 6, $data['alamat_pelanggan'], 1, 0);
    $pdf->Cell(30, 6, ($data['jenis_kelamin'] == 1 ? 'Laki-laki' : 'Perempuan'), 1, 0, 'C');
    $pdf->Cell(30, 6, $data['tanggal_lahir'], 1, 0, 'C');
    $pdf->Cell(20, 6, ($data['status'] == 1 ? 'Aktif' : 'Tidak Aktif'), 1, 1, 'C');
    $no++;
}

$pdf->Output();
