<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';
$query = "SELECT * FROM tb_pengeluaran ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

$total = 0;
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Pengeluaran</title>

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
              <div class="col-sm-6">
                <h3 class="mb-0">Data Transaksi Pengeluaran</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Pengeluaran</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Data Transaksi Pengeluaran</h5>
              <button
                type="button"
                class="btn btn-primary ms-auto"
                data-bs-toggle="modal"
                data-bs-target="#formModal"
              >
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
              </button>
            </div>

            <div class="card-body">
              <table class="table table-bordered">
                <thead class="table-secondary">
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  while ($row = mysqli_fetch_assoc($result)) {
                    $total += $row['nominal'];
                    echo "<tr class='align-middle'>";
                    echo "<td>{$no}</td>";
                    echo "<td>{$row['tanggal']}</td>";
                    echo "<td>{$row['keterangan']}</td>";
                    echo "<td>{$row['kategori']}</td>";
                    echo "<td>Rp " . number_format($row['nominal'], 0, ',', '.') . "</td>";
                    echo "<td>
                      <button 
                        type='button' 
                        class='btn btn-warning btn-sm btn-edit' 
                        data-id='{$row['id']}'
                        data-tanggal='{$row['tanggal']}'
                        data-keterangan='{$row['keterangan']}'
                        data-kategori='{$row['kategori']}'
                        data-nominal='{$row['nominal']}'
                        data-bs-toggle='modal'
                        data-bs-target='#formModal'
                      >
                        <i class='bi bi-pencil-square'></i>
                      </button>
                      <a href=\"hapus_pengeluaran.php?id={$row['id']}\" class=\"btn btn-danger btn-sm\" onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\"><i class='bi bi-trash'></i></a>
                    </td>";
                    echo "</tr>";
                    $no++;
                  }
                  ?>
                  <tr class="fw-bold align-middle">
                    <td colspan="4" class="text-end">Total</td>
                    <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>

      <!-- Modal Tambah/Edit Data -->
      <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="simpan_pengeluaran.php" method="POST">
              <input type="hidden" name="id" id="inputId">
              <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Tambah Transaksi Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="inputTanggal" class="form-label">Tanggal Transaksi</label>
                  <input type="date" class="form-control" name="tanggal" id="inputTanggal" required />
                </div>
                <div class="mb-3">
                  <label for="inputKeterangan" class="form-label">Keterangan</label>
                  <input type="text" class="form-control" name="keterangan" id="inputKeterangan" placeholder="Contoh: Beli Bahan Baku" required />
                </div>
                <div class="mb-3">
                  <label for="inputKategori" class="form-label">Kategori Pengeluaran</label>
                  <select class="form-select" name="kategori" id="inputKategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Operasional">Operasional</option>
                    <option value="Pembelian">Pembelian</option>
                    <option value="Lain-lain">Lain-lain</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="inputNominal" class="form-label">Nominal (Rp)</label>
                  <input type="number" class="form-control" name="nominal" id="inputNominal" placeholder="Contoh: 80000" required />
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

      <!-- Footer -->
      <?php include '../footer.php'; ?>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
          document.getElementById('inputId').value = this.dataset.id;
          document.getElementById('inputTanggal').value = this.dataset.tanggal;
          document.getElementById('inputKeterangan').value = this.dataset.keterangan;
          document.getElementById('inputKategori').value = this.dataset.kategori;
          document.getElementById('inputNominal').value = this.dataset.nominal;

          document.querySelector('#formModal form').action = 'update_pengeluaran.php';
          document.getElementById('formModalLabel').textContent = 'Edit Transaksi Pengeluaran';
        });
      });

      document.getElementById('formModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formModalLabel').textContent = 'Tambah Transaksi Pengeluaran';
        document.querySelector('#formModal form').reset();
        document.getElementById('inputId').value = '';
        document.querySelector('#formModal form').action = 'simpan_pengeluaran.php';
      });
    </script>
  </body>
</html>
