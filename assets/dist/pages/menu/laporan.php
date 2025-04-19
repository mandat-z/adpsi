<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';

// Ambil filter jika ada
$tanggal_dari = $_GET['tanggal_dari'] ?? date('Y-m-01');
$tanggal_sampai = $_GET['tanggal_sampai'] ?? date('Y-m-d');
$jenis = $_GET['jenis'] ?? 'semua';

$pemasukan = [];
$pengeluaran = [];

// Query Pemasukan
$queryPemasukan = "SELECT tanggal, kategori, keterangan, nominal 
                   FROM tb_pemasukan 
                   WHERE tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
if ($jenis !== 'semua') {
    $queryPemasukan .= " AND 'pemasukan' = '$jenis'";
}
$resultPemasukan = mysqli_query($koneksi, $queryPemasukan);
while ($row = mysqli_fetch_assoc($resultPemasukan)) {
    $row['tipe'] = 'pemasukan';
    $pemasukan[] = $row;
}

// Query Pengeluaran
$queryPengeluaran = "SELECT tanggal, kategori, keterangan, nominal 
                     FROM tb_pengeluaran 
                     WHERE tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
if ($jenis !== 'semua') {
    $queryPengeluaran .= " AND 'pengeluaran' = '$jenis'";
}
$resultPengeluaran = mysqli_query($koneksi, $queryPengeluaran);
while ($row = mysqli_fetch_assoc($resultPengeluaran)) {
    $row['tipe'] = 'pengeluaran';
    $pengeluaran[] = $row;
}

// Gabungkan laporan
$laporan = array_merge($pemasukan, $pengeluaran);
usort($laporan, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

// Hitung total
$totalMasuk = array_sum(array_column(array_filter($laporan, fn($x) => $x['tipe'] == 'pemasukan'), 'nominal'));
$totalKeluar = array_sum(array_column(array_filter($laporan, fn($x) => $x['tipe'] == 'pengeluaran'), 'nominal'));
$saldo = $totalMasuk - $totalKeluar;
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <title>Laporan Keuangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- AdminLTE -->
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <?php include '../navbar.php'; ?>
      <?php include '../sidebar.php'; ?>

      <div class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Laporan</h3>
                <p class="text-muted">Data Laporan Pemasukan & Pengeluaran</p>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Laporan</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
              <form method="get">
                <div class="row">
                  <div class="col-md-3">
                    <label for="tanggalDari" class="form-label">Mulai Tanggal</label>
                    <input type="date" id="tanggalDari" name="tanggal_dari" class="form-control" value="<?= $tanggal_dari ?>" required />
                  </div>
                  <div class="col-md-3">
                    <label for="tanggalSampai" class="form-label">Sampai Tanggal</label>
                    <input type="date" id="tanggalSampai" name="tanggal_sampai" class="form-control" value="<?= $tanggal_sampai ?>" required />
                  </div>
                  <div class="col-md-3">
                    <label for="jenis" class="form-label">Jenis</label>
                    <select id="jenis" name="jenis" class="form-select">
                      <option value="semua" <?= $jenis == 'semua' ? 'selected' : '' ?>>Semua Jenis</option>
                      <option value="pemasukan" <?= $jenis == 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                      <option value="pengeluaran" <?= $jenis == 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                    </select>
                  </div>
                  <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="card mt-4">
            <div class="card-header">
              <h5 class="mb-0">Laporan Pemasukan & Pengeluaran</h5>
            </div>
            <div class="card-body">
              <table class="table table-sm mb-3">
                <tr><th width="30%">Dari Tanggal</th><td><?= $tanggal_dari ?></td></tr>
                <tr><th>Sampai Tanggal</th><td><?= $tanggal_sampai ?></td></tr>
                <tr><th>Jenis</th><td><?= $jenis == 'semua' ? 'Semua Jenis' : ucfirst($jenis) ?></td></tr>
              </table>

              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                  <thead class="table-light text-center">
                    <tr>
                      <th rowspan="2">No</th>
                      <th rowspan="2">Tanggal</th>
                      <th rowspan="2">Kategori</th>
                      <th rowspan="2">Keterangan</th>
                      <th colspan="2">Jenis</th>
                    </tr>
                    <tr>
                      <th>Pemasukan</th>
                      <th>Pengeluaran</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; foreach ($laporan as $row): ?>
                      <tr class="align-middle text-center">
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                        <td class="text-start"><?= $row['kategori'] ?></td>
                        <td class="text-start"><?= $row['keterangan'] ?></td>
                        <td><?= $row['tipe'] == 'pemasukan' ? 'Rp. ' . number_format($row['nominal'], 0, ',', '.') : '-' ?></td>
                        <td><?= $row['tipe'] == 'pengeluaran' ? 'Rp. ' . number_format($row['nominal'], 0, ',', '.') : '-' ?></td>
                      </tr>
                    <?php endforeach; ?>

                    <tr class="fw-bold text-center">
                      <td colspan="4" class="text-end">TOTAL</td>
                      <td class="text-success">Rp. <?= number_format($totalMasuk, 0, ',', '.') ?></td>
                      <td class="text-danger">Rp. <?= number_format($totalKeluar, 0, ',', '.') ?></td>
                    </tr>
                    <tr class="fw-bold text-center bg-primary text-white">
                      <td colspan="4" class="text-end">SALDO</td>
                      <td colspan="2">Rp. <?= number_format($saldo, 0, ',', '.') ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="d-flex gap-2 mt-3">
              <a href="cetak_pdf.php?tanggal_dari=<?= $tanggal_dari ?>&tanggal_sampai=<?= $tanggal_sampai ?>&jenis=<?= $jenis ?>" target="_blank" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
              </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include '../footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
