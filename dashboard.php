<?php
session_start();
include('config/koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];

$statistik = [
    'total' => 0,
    'selesai' => 0,
    'belum_selesai' => 0
];

if ($role === 'admin') {
    $q1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan");
    $q2 = mysqli_query($conn, "SELECT COUNT(*) AS selesai FROM laporan WHERE status='selesai'");
    $q3 = mysqli_query($conn, "SELECT COUNT(*) AS belum FROM laporan WHERE status!='selesai'");

    $statistik['total'] = mysqli_fetch_assoc($q1)['total'];
    $statistik['selesai'] = mysqli_fetch_assoc($q2)['selesai'];
    $statistik['belum_selesai'] = mysqli_fetch_assoc($q3)['belum'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Pengaduan Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    .bento-card {
        border-radius: 1rem;
        transition: all 0.3s ease;
        background-color: #fff;
        color: #212529;
    }

    .bento-card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        background-color: #0d6efd; /* Warna biru muda */
        color: #fff;
    }

    .bento-card:hover .card-title,
    .bento-card:hover .text-muted {
        color: #f8f9fa;
    }

    .card-header.bg-danger {
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
}
.p-3.border {
    transition: all 0.3s ease;
}
.p-3.border:hover {
    background-color: #e9ecef;
    transform: scale(1.02);
}

</style>


<body style="background-color: #f8f9fa;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Pengaduan Warga Apartemen Nusa 2</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">Halo, <?= htmlspecialchars($username) ?> (<?= $role ?>)</span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center">
                        <h3 class="card-title mb-3">Selamat datang, <?= htmlspecialchars($username) ?>!</h3>
                        <p class="card-text">Silakan pilih tindakan di bawah ini:</p>
                        <div class="d-grid gap-3 mt-4">
                            <?php if ($role === 'warga'): ?>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">
            <a href="laporan.php" class="text-decoration-none text-dark">
                <div class="card shadow-sm border-0 bento-card text-center h-100">
                    <div class="card-body">
                        <div class="display-4 mb-3">📝</div>
                        <h5 class="card-title fw-bold">Buat Laporan</h5>
                        <p class="text-muted">Laporkan masalah yang Anda hadapi di lingkungan Anda.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="lihat_laporan.php" class="text-decoration-none text-dark">
                <div class="card shadow-sm border-0 bento-card text-center h-100">
                    <div class="card-body">
                        <div class="display-4 mb-3">📄</div>
                        <h5 class="card-title fw-bold">Lihat Laporan Saya</h5>
                        <p class="text-muted">Pantau status dan tanggapan dari laporan Anda.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <?php if ($role === 'warga'): ?>
<!-- Nomor Darurat Penting -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-danger text-white fw-bold text-center">
        ☎️ Nomor Darurat Penting
    </div>
    <div class="card-body p-4">
        <div class="row row-cols-1 row-cols-md-2 g-3">
            <div class="col">
                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                    🏢 <strong>Manajemen</strong> <span class="badge bg-danger rounded-pill">889</span>
                </div>
            </div>
            <div class="col">
                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                    👨‍💼 <strong>Security</strong> <span class="badge bg-danger rounded-pill">888</span>
                </div>
            </div>
            <div class="col">
                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                    🚨 <strong>Polisi</strong> <span class="badge bg-danger rounded-pill">110</span>
                </div>
            </div>
            <div class="col">
                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                    🚑 <strong>Ambulans</strong> <span class="badge bg-success rounded-pill">118 / 119</span>
                </div>
            </div>
            <div class="col">
                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                    🔥 <strong>Pemadam</strong> <span class="badge bg-warning rounded-pill">113</span>
                </div>
            </div>
            <div class="col">
                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                    🆘 <strong>SAR/Basarnas</strong> <span class="badge bg-primary rounded-pill">115</span>
                </div>
            </div>
            <div class="col">
                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                    ⚡ <strong>PLN</strong> <span class="badge bg-dark rounded-pill">123</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php elseif ($role === 'admin'): ?>

                                <a href="admin/tanggapan.php" class="btn btn-warning btn-lg">📋 Kelola Laporan</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Statistik -->
<!-- Statistik Bento Style -->
<?php if ($role === 'admin'): ?>
<div class="mb-5">
    <h5 class="text-center mb-4">📊 Statistik Laporan</h5>
    <div class="row row-cols-1 row-cols-md-3 g-4">

        <!-- Total Aduan -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="mb-3">
                        <div class="display-6">📨</div>
                        <h5 class="fw-bold mt-2">Total Aduan</h5>
                    </div>
                    <h2 class="fw-bold"><?= $statistik['total'] ?></h2>
                </div>
            </div>
        </div>

        <!-- Aduan Selesai -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #1cc88a, #12875b);">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="mb-3">
                        <div class="display-6">✅</div>
                        <h5 class="fw-bold mt-2">Aduan Selesai</h5>
                    </div>
                    <h2 class="fw-bold"><?= $statistik['selesai'] ?></h2>
                </div>
            </div>
        </div>

        <!-- Aduan Belum Selesai -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #f6c23e, #e0a800);">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="mb-3">
                        <div class="display-6">⏳</div>
                        <h5 class="fw-bold mt-2">Belum Selesai</h5>
                    </div>
                    <h2 class="fw-bold"><?= $statistik['belum_selesai'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card mt-5 shadow-sm border-0">
        <div class="card-header bg-light text-center fw-semibold">
            Grafik Distribusi Status Aduan
        </div>
        <div class="card-body">
            <canvas id="statusChart" height="100"></canvas>
        </div>
    </div>
</div>
<?php endif; ?>



                <p class="text-center text-muted">Hak akses: <strong><?= $role ?></strong></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 bg-light text-muted">
        <small>&copy; <?= date('Y') ?> Aplikasi Pengaduan Warga Apartemen Nusa</small>
    </footer>

    <!-- Chart.js Pie Chart Script -->
    <?php if ($role === 'admin'): ?>
    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Selesai', 'Belum Selesai'],
                datasets: [{
                    data: [<?= $statistik['selesai'] ?>, <?= $statistik['belum_selesai'] ?>],
                    backgroundColor: ['#198754', '#ffc107'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
