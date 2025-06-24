<?php
session_start();
include('../config/koneksi.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['selesaikan'])) {
    $id = (int)$_POST['id'];
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar_admin']);
    mysqli_query($conn, "UPDATE laporan SET status='selesai', komentar_admin='$komentar' WHERE id=$id");
    header("Location: tanggapan.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM laporan WHERE id=$id");
    header("Location: tanggapan.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM laporan ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon\Frame56.png" type="image/png">
    <style>
        .bento-card {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        .bento-card:hover {
            transform: scale(1.01);
        }
        .foto-laporan {
            max-height: 200px;
            object-fit: cover;
            width: 100%;
            cursor: pointer;
        }
        .badge-status {
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 Kelola Laporan Warga</h2>
        <a href="../dashboard.php" class="btn btn-outline-secondary">← Kembali ke Dashboard</a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <?php
            $status = $row['status'];
            $badge = match($status) {
                'baru' => 'secondary',
                'proses' => 'warning',
                'selesai' => 'success',
                default => 'dark',
            };
            ?>
            <div class="col">
                <div class="card bento-card h-100">
                    <?php if (!empty($row['foto'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>"
                             class="foto-laporan"
                             alt="Foto Laporan"
                             data-bs-toggle="modal"
                             data-bs-target="#imageModal"
                             data-image="../uploads/<?= htmlspecialchars($row['foto']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title mb-2"><?= htmlspecialchars($row['judul']) ?>
                            <span class="badge bg-<?= $badge ?> badge-status float-end"><?= strtoupper($status) ?></span>
                        </h5>
                        <p class="text-muted mb-1"><small>🕒 <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></small></p>
                        <p><strong>👤 Pengguna:</strong> <?= htmlspecialchars($row['user']) ?></p>
                        <p><strong>📂 Kategori:</strong> <?= htmlspecialchars($row['kategori']) ?></p>
                        <p><strong>📝 Isi:</strong><br><?= nl2br(htmlspecialchars($row['isi'])) ?></p>
                        <hr>
                        <p><strong>📣 Komentar Admin:</strong><br><?= nl2br(htmlspecialchars($row['komentar_admin'] ?? '-')) ?></p>

                        <?php if ($status != 'selesai'): ?>
                        <form method="post" class="mt-3">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="mb-3">
                                <label for="komentar_admin" class="form-label fw-semibold">🗨️ Tanggapan Admin</label>
                                <textarea name="komentar_admin" class="form-control" rows="3" placeholder="Tulis tanggapan..." required></textarea>
                            </div>
                            <div class="d-grid mb-2">
                                <button type="submit" name="selesaikan" class="btn btn-success">
                                    ✅ Tandai Selesai & Kirim Komentar
                                </button>
                            </div>
                        </form>
                        <?php endif; ?>

                        <div class="d-grid">
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-outline-danger"
                               onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                🗑️ Hapus Laporan Ini
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal untuk preview gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-0 rounded-4">
            <div class="modal-body p-0 text-center">
                <img src="" id="modalImage" class="img-fluid" style="max-height: 80vh; max-width: 100%; object-fit: contain;">
            </div>
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Script Bootstrap & Modal Handler -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    imageModal.addEventListener('show.bs.modal', function (event) {
        const triggerImg = event.relatedTarget;
        const imgSrc = triggerImg.getAttribute('data-image');
        modalImage.src = imgSrc;
    });
</script>
</body>
</html>
