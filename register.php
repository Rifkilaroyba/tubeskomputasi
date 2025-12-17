<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mobie Legend Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #3de4bdff 0%, #4bdc23ff 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .register-card {
            background: linear-gradient(#3de4bdff 0%, #4bdc23ff);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.65);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-card p-5">
                    <h2 class="text-center mb-4"></i>Registrasi Akun Mobile Legend Store</h2>
                    <?php
                    if(isset($_POST['register'])) {
                        $username = $_POST['username'];
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $full_name = $_POST['full_name'];
                        $email = $_POST['email'];
                        
                        $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, email) VALUES (?, ?, ?, ?)");
                        if($stmt->execute([$username, $password, $full_name, $email])) {
                            echo '<div class="alert alert-success">Registrasi berhasil! <a href="index.php">Login disini</a></div>';
                        } else {
                            echo '<div class="alert alert-danger">Registrasi gagal!</div>';
                        }
                    }
                    ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
