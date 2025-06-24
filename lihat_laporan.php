<?php
session_start();
include('config/koneksi.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'warga') {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];

// Proses hapus laporan
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $cek = mysqli_query($conn, "SELECT * FROM laporan WHERE id='$id' AND user='$username'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM laporan WHERE id='$id'");
        echo "<script>alert('✅ Laporan berhasil dihapus!'); window.location='lihat_laporan.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Gagal menghapus laporan.'); window.location='lihat_laporan.php';</script>";
        exit;
    }
}

$result = mysqli_query($conn, "SELECT * FROM laporan WHERE user='$username' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon\Frame56.png" type="image/png">
    <style>
        .laporan-card {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        .laporan-card .card-header {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .laporan-card .badge {
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📄 Laporan Saya</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <div class="alert alert-info text-center">
            📭 Tidak ada laporan yang ditemukan.
        </div>
    <?php else: ?>
        <?php while($row = mysqli_fetch_assoc($result)):
            $status = $row['status'];
            $badge = match($status) {
                'baru' => 'secondary',
                'proses' => 'warning',
                'selesai' => 'success',
                default => 'dark',
            };
        ?>
        <div class="card laporan-card">
            <div class="card-header d-flex justify-content-between">
                <span>📝 <?= htmlspecialchars($row['judul']) ?></span>
                <span class="badge bg-<?= $badge ?>"><?= strtoupper($status) ?></span>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>📂 Kategori:</strong> <?= htmlspecialchars($row['kategori']) ?></p>
                <p class="mb-1"><strong>📍 Alamat:</strong> <?= htmlspecialchars($row['alamat']) ?></p>
                <p class="mb-1"><strong>🕒 Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></p>
                <p class="mb-3"><strong>📄 Isi Laporan:</strong><br><?= nl2br(htmlspecialchars($row['isi'])) ?></p>

                <?php if (!empty($row['foto'])): ?>
                    <div class="mb-3">
                        <strong>📷 Foto:</strong><br>
                        <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Laporan" class="img-fluid rounded" style="max-width:300px;">
                    </div>
                <?php endif; ?>

                <hr>
                <p class="mb-0"><strong>📣 Komentar Admin:</strong></p>
                <div class="border rounded p-2 bg-white mt-1">
                    <?= $row['komentar_admin']
                        ? nl2br(htmlspecialchars($row['komentar_admin']))
                        : '<span class="text-muted fst-italic">Belum ada komentar.</span>' ?>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="lihat_laporan.php?hapus=<?= $row['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                       🗑️ Hapus
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
</body>
</html>
