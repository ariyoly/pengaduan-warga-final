<?php
session_start();
include('config/koneksi.php');

// Cek autentikasi & role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'warga') {
    header("Location: index.php");
    exit;
}

$feedback = '';

// Proses kirim laporan
if (isset($_POST['kirim'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $user = $_SESSION['username'];

    // Inisialisasi variabel foto
    $fotoName = '';

    // Cek apakah file diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoName = 'foto_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $fotoName);
    }

    $query = mysqli_query($conn, "INSERT INTO laporan(user, kategori, judul, isi, alamat, foto) 
                                  VALUES('$user', '$kategori', '$judul', '$isi', '$alamat', '$fotoName')");

    if ($query) {
        $feedback = '<div class="alert alert-success mt-3">✅ Laporan berhasil dikirim!</div>';
    } else {
        $feedback = '<div class="alert alert-danger mt-3">❌ Gagal mengirim laporan. Silakan coba lagi.</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Laporan Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon\Frame56.png" type="image/png">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .form-card {
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .form-card .card-header {
            background-color: #0d6efd;
            color: white;
            padding: 1.25rem;
        }
        .form-card .card-body {
            padding: 2rem;
        }
        .form-card .card-footer {
            background-color: #f8f9fa;
            padding: 1rem 2rem;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?= $feedback ?>
            <div class="card form-card">
                <div class="card-header">
                    <h4 class="mb-0">📝 Formulir Pengaduan Warga</h4>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold">📌 Judul Laporan</label>
                            <input type="text" id="judul" name="judul" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label fw-semibold">📂 Kategori</label>
                            <select id="kategori" name="kategori" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Jalan Rusak">Jalan Rusak</option>
                                <option value="Sampah">Sampah</option>
                                <option value="Kasus Kejahatan">Kasus Kejahatan</option>
                                <option value="Banjir">Banjir</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-semibold">📍 Alamat Kejadian</label>
                            <input type="text" id="alamat" name="alamat" class="form-control form-control-lg" placeholder="Contoh: Blok A No 99" required>
                        </div>
                        <div class="mb-3">
    <label for="foto" class="form-label fw-semibold">📸 Upload Foto (Opsional)</label>
    <input type="file" id="foto" name="foto" class="form-control form-control-lg" accept="image/*" onchange="previewFoto(event)">
    <div class="mt-3 text-center">
        <img id="preview" src="#" alt="Preview Foto" style="display: none; max-width: 100%; max-height: 300px; border-radius: 12px; box-shadow: 0 0 6px rgba(0,0,0,0.1);">
    </div>
</div>


                        <div class="mb-3">
                            <label for="isi" class="form-label fw-semibold">🗒️ Isi Laporan</label>
                            <textarea id="isi" name="isi" rows="5" class="form-control form-control-lg" placeholder="Tulis laporan Anda di sini..." required></textarea>
                        </div>
                        <div class="d-grid d-md-flex justify-content-between gap-2">
                            <a href="dashboard.php" class="btn btn-outline-secondary btn-lg">← Kembali</a>
                            <button type="submit" name="kirim" class="btn btn-success btn-lg">📤 Kirim Laporan</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-end text-muted">
                    Masuk sebagai: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Js preview foto -->
<script>
function previewFoto(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
