<?php
require_once 'config.php';
require_once 'midtrans_config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header('Location: dashboard.php');
    exit();
}

$order_id = $_GET['order_id'];

/* ===== AMBIL DATA ORDER ===== */
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: dashboard.php');
    exit();
}

/* ===== CEK STATUS KE MIDTRANS ===== */
$showSuccessPopup = false;

try {
    $result = \Midtrans\Transaction::status('ORDER-' . $order_id);

    // PAKSA JADI OBJECT (AMAN UNTUK IDE & RUNTIME)
    $status = json_decode(json_encode($result));

    if (
        isset($status->transaction_status) &&
        in_array($status->transaction_status, ['settlement', 'capture'])
    ) {

        if ($order['payment_status'] !== 'success') {
            $stmt = $conn->prepare("
                UPDATE orders 
                SET payment_status = 'success'
                WHERE id = ?
            ");
            $stmt->execute([$order_id]);
        }

        $showSuccessPopup = true;
    }
} catch (Exception $e) {
    // diamkan, halaman tetap tampil
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #66eac0ff 0%, #53be01ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 60px 40px;
        }

        .success-icon {
            font-size: 100px;
            color: #28a745;
            animation: scaleIn 0.5s ease-in-out;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body>

    <?php if ($showSuccessPopup): ?>
        <script>
            alert("✅ Pembayaran berhasil!");
            window.location.href = "dashboard.php";
        </script>
    <?php endif; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="success-card">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="mb-3">Pesanan Dibuat</h2>
                    <p class="lead">
                        <?php if ($order['payment_status'] === 'success'): ?>
                            Pembayaran telah berhasil
                        <?php else: ?>
                            Silakan selesaikan pembayaran Anda
                        <?php endif; ?>
                    </p>

                    <div class="card my-4">
                        <div class="card-body">
                            <div class="row text-start">
                                <div class="col-6"><strong>No. Pesanan:</strong></div>
                                <div class="col-6">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></div>

                                <div class="col-6 mt-2"><strong>Tipe:</strong></div>
                                <div class="col-6 mt-2"><?= $order['order_type']; ?></div>

                                <div class="col-6 mt-2"><strong>Pembayaran:</strong></div>
                                <div class="col-6 mt-2"><?= $order['payment_method']; ?></div>

                                <div class="col-6 mt-2"><strong>Total:</strong></div>
                                <div class="col-6 mt-2 text-success">
                                    <strong>Rp <?= number_format($order['total_amount'], 0, ',', '.'); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($order['payment_status'] !== 'success'): ?>
                        <a href="check_status.php?order_id=<?= $order_id ?>"
                            class="btn btn-warning btn-lg">
                            <i class="fas fa-sync"></i> Check Status Pembayaran
                        </a>

                    <?php endif; ?>

                    <a href="dashboard.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Kembali ke Dashboard
                    </a>

                </div>
            </div>
        </div>
    </div>
</body>

</html>