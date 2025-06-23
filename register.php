<?php
include('config/koneksi.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'warga';

    if ($username == '' || $password == '' || $role == '') {
        $error = 'Semua field wajib diisi.';
    } else {
        $cek = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $cek->bind_param("s", $username);
        $cek->execute();
        $result = $cek->get_result();

        if ($result->num_rows == 0) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed, $role);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil! <a href='index.php'>Login sekarang</a>";
            } else {
                $error = "Terjadi kesalahan saat registrasi.";
            }
        } else {
            $error = 'Username sudah terdaftar.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Sistem Pengaduan Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">Registrasi Akun</h3>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php elseif ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Daftar Sebagai</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" value="warga" checked>
                                    <label class="form-check-label">Warga</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" value="admin">
                                    <label class="form-check-label">Admin</label>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="register" class="btn btn-success">Daftar</button>
                            </div>
                            <p class="text-center mt-3 mb-0">
                                Sudah punya akun? <a href="index.php">Login di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
                <p class="text-center mt-3 text-muted">&copy; <?= date('Y') ?> Sistem Pengaduan Warga</p>
            </div>
        </div>
    </div>
</body>
</html>
