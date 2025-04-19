<?php
require 'C:/xampp/htdocs/adpsi/fpdf/fpdf.php'; // Pastikan path-nya sesuai

include 'C:/xampp/htdocs/adpsi/config/config.php';

$tanggal_dari = $_GET['tanggal_dari'] ?? date('Y-m-01');
$tanggal_sampai = $_GET['tanggal_sampai'] ?? date('Y-m-d');
$jenis = $_GET['jenis'] ?? 'semua';

$pemasukan = [];
$pengeluaran = [];

$queryPemasukan = "SELECT tanggal, kategori, keterangan, nominal 
                   FROM tb_pemasukan 
                   WHERE tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
$resultPemasukan = mysqli_query($koneksi, $queryPemasukan);
while ($row = mysqli_fetch_assoc($resultPemasukan)) {
    $row['tipe'] = 'pemasukan';
    $pemasukan[] = $row;
}

$queryPengeluaran = "SELECT tanggal, kategori, keterangan, nominal 
                     FROM tb_pengeluaran 
                     WHERE tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
$resultPengeluaran = mysqli_query($koneksi, $queryPengeluaran);
while ($row = mysqli_fetch_assoc($resultPengeluaran)) {
    $row['tipe'] = 'pengeluaran';
    $pengeluaran[] = $row;
}

$laporan = array_merge($pemasukan, $pengeluaran);
usort($laporan, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

$totalMasuk = array_sum(array_column(array_filter($laporan, fn($x) => $x['tipe'] == 'pemasukan'), 'nominal'));
$totalKeluar = array_sum(array_column(array_filter($laporan, fn($x) => $x['tipe'] == 'pengeluaran'), 'nominal'));
$saldo = $totalMasuk - $totalKeluar;

// Mulai PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Laporan Keuangan', 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, 'Periode:', 0, 0);
$pdf->Cell(50, 6, $tanggal_dari . ' s/d ' . $tanggal_sampai, 0, 1);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 8, 'No', 1);
$pdf->Cell(25, 8, 'Tanggal', 1);
$pdf->Cell(35, 8, 'Kategori', 1);
$pdf->Cell(50, 8, 'Keterangan', 1);
$pdf->Cell(30, 8, 'Pemasukan', 1);
$pdf->Cell(30, 8, 'Pengeluaran', 1);

$pdf->SetFont('Arial', '', 10);
$no = 1;
foreach ($laporan as $row) {
    $pdf->Ln();
    $pdf->Cell(10, 8, $no++, 1);
    $pdf->Cell(25, 8, date('d-m-Y', strtotime($row['tanggal'])), 1);
    $pdf->Cell(35, 8, $row['kategori'], 1);
    $pdf->Cell(50, 8, $row['keterangan'], 1);
    $pdf->Cell(30, 8, $row['tipe'] == 'pemasukan' ? number_format($row['nominal']) : '-', 1, 0, 'R');
    $pdf->Cell(30, 8, $row['tipe'] == 'pengeluaran' ? number_format($row['nominal']) : '-', 1, 0, 'R');
}

$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(120, 8, 'TOTAL', 1);
$pdf->Cell(30, 8, number_format($totalMasuk), 1, 0, 'R');
$pdf->Cell(30, 8, number_format($totalKeluar), 1, 0, 'R');

$pdf->Ln();
$pdf->Cell(120, 8, 'SALDO', 1);
$pdf->Cell(60, 8, number_format($saldo), 1, 0, 'R');

$pdf->Output('I', 'Laporan_Keuangan.pdf');
?>
