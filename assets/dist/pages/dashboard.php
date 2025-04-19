<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';

// Query total pemasukan
$queryPemasukan = "SELECT SUM(nominal) AS total_pemasukan FROM tb_pemasukan";
$resultPemasukan = mysqli_query($koneksi, $queryPemasukan);
$totalPemasukan = mysqli_fetch_assoc($resultPemasukan)['total_pemasukan'] ?? 0;

// Query total pengeluaran
$queryPengeluaran = "SELECT SUM(nominal) AS total_pengeluaran FROM tb_pengeluaran";
$resultPengeluaran = mysqli_query($koneksi, $queryPengeluaran);
$totalPengeluaran = mysqli_fetch_assoc($resultPengeluaran)['total_pengeluaran'] ?? 0;

// Calculate profit
$profit = $totalPemasukan - $totalPengeluaran;

// Query total stok
$queryStok = "SELECT SUM(stok) AS total_stok FROM tb_stok_barang";
$resultStok = mysqli_query($koneksi, $queryStok);
$totalStok = mysqli_fetch_assoc($resultStok)['total_stok'] ?? 0;

// Fetch sales data dynamically based on date range
$startDate = $_GET['start_date'] ?? date('Y-m-01'); // Default to the first day of the current month
$endDate = $_GET['end_date'] ?? date('Y-m-d'); // Default to today

$querySales = "SELECT tanggal, SUM(nominal) AS total_penjualan 
               FROM tb_pemasukan 
               WHERE tanggal BETWEEN '$startDate' AND '$endDate' 
               GROUP BY tanggal 
               ORDER BY tanggal ASC";
$resultSales = mysqli_query($koneksi, $querySales);

$salesData = [];
$salesLabels = [];
while ($row = mysqli_fetch_assoc($resultSales)) {
    $salesLabels[] = $row['tanggal'];
    $salesData[] = $row['total_penjualan'];
}

// Fetch stock data for the pie chart
$queryStock = "SELECT nama_barang, stok FROM tb_stok_barang";
$resultStock = mysqli_query($koneksi, $queryStock);

$stockLabels = [];
$stockData = [];
while ($row = mysqli_fetch_assoc($resultStock)) {
    $stockLabels[] = $row['nama_barang'];
    $stockData[] = $row['stok'];
}

// Fetch items with stock less than 5
$queryLowStock = "SELECT nama_barang, stok FROM tb_stok_barang WHERE stok < 5";
$resultLowStock = mysqli_query($koneksi, $queryLowStock);

$lowStockItems = [];
while ($row = mysqli_fetch_assoc($resultLowStock)) {
    $lowStockItems[] = $row;
}
?>
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!--end::App Content Header-->

  <!--begin::App Content-->
  <div class="app-content">
    <div class="container-fluid">
      <!-- Warning Section -->
      <?php if (!empty($lowStockItems)) { ?>
      <div class="alert alert-warning">
        <strong>Perhatian!</strong> Stok barang berikut kurang dari 5:
        <ul>
          <?php foreach ($lowStockItems as $item) { ?>
            <li><?= $item['nama_barang'] ?> (Stok: <?= $item['stok'] ?>)</li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>

      <!--begin::Row - Statistik Box-->
      <div class="row">
        <!-- Pemasukan -->
        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-success">
            <div class="inner">
              <h3>Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></h3>
              <p>Total Pemasukan</p>
            </div>
            <i class="bi bi-graph-up fs-1 small-box-icon"></i>
            <a href="menu/pemasukan.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
              Detail <i class="bi bi-link-45deg"></i>
            </a>
          </div>
        </div>

        <!-- Pengeluaran -->
        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-danger">
            <div class="inner">
              <h3>Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></h3>
              <p>Total Pengeluaran</p>
            </div>
            <i class="bi bi-cash-stack fs-1 small-box-icon"></i>
            <a href="menu/pengeluaran.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
              Detail <i class="bi bi-link-45deg"></i>
            </a>
          </div>
        </div>

        <!-- Profit -->
        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-primary">
            <div class="inner">
              <h3>Rp <?= number_format($profit, 0, ',', '.') ?></h3>
              <p>Total Profit</p>
            </div>
            <i class="bi bi-coin fs-1 small-box-icon"></i>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
              Detail <i class="bi bi-link-45deg"></i>
            </a>
          </div>
        </div>

        <!-- Stok -->
        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-warning">
            <div class="inner">
              <h3><?= number_format($totalStok, 0, ',', '.') ?></h3>
              <p>Total Stok Barang</p>
            </div>
            <i class="bi bi-box-seam fs-1 small-box-icon"></i>
            <a href="menu/stok.php" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
              Detail <i class="bi bi-link-45deg"></i>
            </a>
          </div>
        </div>
      </div>
      <!--end::Row - Statistik Box-->

      <!--begin::Row - Grafik & Stok-->
      <div class="row">
        <!-- Sales Value Chart -->
        <div class="col-lg-7">
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title">Sales Value</h3>
              <form method="GET" class="d-flex align-items-center">
                <label for="start_date" class="me-2">Dari:</label>
                <input type="date" id="start_date" name="start_date" class="form-control me-2" value="<?= $startDate ?>" required>
                <label for="end_date" class="me-2">Sampai:</label>
                <input type="date" id="end_date" name="end_date" class="form-control me-2" value="<?= $endDate ?>" required>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
              </form>
            </div>
            <div class="card-body">
              <canvas id="revenue-chart"></canvas>
            </div>
          </div>
        </div>

        <!-- Stok Barang Chart -->
        <div class="col-lg-5">
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title">Stok Barang</h3>
            </div>
            <div class="card-body">
              <canvas id="stock-pie-chart" style="height: 250px;"></canvas>
            </div>
          </div>
        </div>
      </div>
      <!--end::Row - Grafik & Stok-->
    </div>
  </div>
  <!--end::App Content-->
</main>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Pie Chart Script -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('stock-pie-chart').getContext('2d');

    const stockLabels = <?= json_encode($stockLabels) ?>;
    const stockData = <?= json_encode($stockData) ?>;

    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: stockLabels.map((label, index) => `${label} (Stok: ${stockData[index]})`),
        datasets: [{
          data: stockData,
          backgroundColor: [
            '#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8', '#fd7e14'
          ],
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' }
        },
      },
    });
  });
</script>

<!-- Line Chart with Date Range -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const revenueCtx = document.getElementById('revenue-chart').getContext('2d');

    const salesLabels = <?= json_encode($salesLabels) ?>;
    const salesData = <?= json_encode($salesData) ?>;

    new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: salesLabels,
        datasets: [{
          label: 'Penjualan',
          data: salesData,
          fill: true,
          borderColor: '#007bff',
          backgroundColor: 'rgba(0, 123, 255, 0.1)'
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  });
</script>
