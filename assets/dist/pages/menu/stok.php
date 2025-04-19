<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';
$query = "SELECT * FROM tb_stok_barang ORDER BY tanggal_masuk DESC";
$result = mysqli_query($koneksi, $query);

// Fetch items with stock less than 5
$queryLowStock = "SELECT nama_barang, stok FROM tb_stok_barang WHERE stok < 5";
$resultLowStock = mysqli_query($koneksi, $queryLowStock);

$lowStockItems = [];
while ($row = mysqli_fetch_assoc($resultLowStock)) {
    $lowStockItems[] = $row;
}

$no = 1;
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Stok Barang</title>

    <!-- Bootstrap & AdminLTE -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />

    <!-- Font -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    />
  </head>

  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <!-- Navbar -->
      <?php include '../navbar.php'; ?>
      <!-- Sidebar -->
      <?php include '../sidebar.php'; ?>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Data Stok Barang</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Stok Barang</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
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

          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Data Stok Barang</h5>
              <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#formModalStok">
                <i class="bi bi-plus-lg me-1"></i> Tambah Barang
              </button>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <thead class="table-secondary">
                  <tr>
                    <th style="width: 10px">No</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Tanggal Masuk</th>
                    <th style="width: 100px">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="align-middle">
                      <td><?= $no ?></td>
                      <td><?= $row['nama_barang'] ?></td>
                      <td><?= $row['jenis'] ?></td>
                      <td><?= $row['stok'] ?></td>
                      <td><?= $row['satuan'] ?></td>
                      <td><?= $row['tanggal_masuk'] ?></td>
                      <td>
                        <button 
                          type="button"
                          class="btn btn-warning btn-sm btn-edit-stok"
                          data-id="<?= $row['id'] ?>"
                          data-nama_barang="<?= $row['nama_barang'] ?>"
                          data-jenis="<?= $row['jenis'] ?>"
                          data-stok="<?= $row['stok'] ?>"
                          data-satuan="<?= $row['satuan'] ?>"
                          data-tanggal_masuk="<?= $row['tanggal_masuk'] ?>"
                          data-bs-toggle="modal"
                          data-bs-target="#formModalStok"
                        >
                          <i class="bi bi-pencil-square"></i>
                        </button>
                        <a href="hapus_stok.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <?php $no++; ?>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Modal Tambah/Edit Barang -->
          <div class="modal fade" id="formModalStok" tabindex="-1" aria-labelledby="formModalStokLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="simpan_stok.php" method="POST">
                  <input type="hidden" name="id" id="inputIdStok" />
                  <div class="modal-header">
                    <h5 class="modal-title" id="formModalStokLabel">Tambah Stok Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="inputNamaBarang" class="form-label">Nama Barang</label>
                      <input type="text" class="form-control" name="nama_barang" id="inputNamaBarang" required />
                    </div>
                    <div class="mb-3">
                      <label for="inputJenis" class="form-label">Jenis</label>
                      <select class="form-select" name="jenis" id="inputJenis" required>
                        <option value="Bahan Baku">Bahan Baku</option>
                        <option value="Bahan Jadi">Bahan Jadi</option>
                        <option value="Lain-lain">Lain-lain</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="inputStok" class="form-label">Jumlah Stok</label>
                      <input type="number" class="form-control" name="stok" id="inputStok" required />
                    </div>
                    <div class="mb-3">
                      <label for="inputSatuan" class="form-label">Satuan</label>
                      <input type="text" class="form-control" name="satuan" id="inputSatuan" required />
                    </div>
                    <div class="mb-3">
                      <label for="inputTanggalMasuk" class="form-label">Tanggal Masuk</label>
                      <input type="date" class="form-control" name="tanggal_masuk" id="inputTanggalMasuk" required />
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- End Modal -->
        </div>
      </main>

      <!-- Footer -->
      <?php include '../footer.php'; ?>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script untuk edit -->
    <script>
      document.querySelectorAll('.btn-edit-stok').forEach(button => {
        button.addEventListener('click', function () {
          document.getElementById('formModalStokLabel').textContent = 'Edit Stok Barang';
          document.getElementById('inputIdStok').value = this.dataset.id;
          document.getElementById('inputNamaBarang').value = this.dataset.nama_barang;
          document.getElementById('inputJenis').value = this.dataset.jenis;
          document.getElementById('inputStok').value = this.dataset.stok;
          document.getElementById('inputSatuan').value = this.dataset.satuan;
          document.getElementById('inputTanggalMasuk').value = this.dataset.tanggal_masuk;
          document.querySelector('#formModalStok form').action = 'update_stok.php';
        });
      });

      document.getElementById('formModalStok').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formModalStokLabel').textContent = 'Tambah Stok Barang';
        document.querySelector('#formModalStok form').reset();
        document.getElementById('inputIdStok').value = '';
        document.querySelector('#formModalStok form').action = 'simpan_stok.php';
      });
    </script>
  </body>
</html>
