<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header('Location: dashboard.php');
    exit();
}

/* ================= HITUNG TOTAL ================= */
$total = 0;
foreach ($_SESSION['cart'] as $menu_id => $qty) {
    $stmt = $conn->prepare("SELECT price FROM menu WHERE id = ?");
    $stmt->execute([$menu_id]);
    $item = $stmt->fetch();
    $total += $item['price'] * $qty;
}

/* ================= PROSES PEMBAYARAN ================= */
if (isset($_POST['process_payment'])) {

    $payment_method = $_POST['payment_method'] ?? null;
    $ml_user_id     = $_POST['ml_user_id'] ?? null;
    $ml_server      = $_POST['ml_server'] ?? null;
    $order_type     = $_SESSION['order_type'] ?? 'Top Up Diamond';

    if (!$payment_method || !$ml_user_id || !$ml_server) {
        die('Data pembayaran tidak lengkap');
    }

    /* ===== INSERT ORDERS ===== */
    $stmt = $conn->prepare("
        INSERT INTO orders 
        (user_id, ml_user_id, ml_server, order_type, payment_method, total_amount, payment_status)
        VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $_SESSION['user_id'],
        $ml_user_id,
        $ml_server,
        $order_type,
        $payment_method,
        $total
    ]);

    $order_id = $conn->lastInsertId();

    /* ===== INSERT ORDER DETAILS ===== */
    foreach ($_SESSION['cart'] as $menu_id => $qty) {
        $stmt = $conn->prepare("SELECT price FROM menu WHERE id = ?");
        $stmt->execute([$menu_id]);
        $item = $stmt->fetch();

        $stmt = $conn->prepare("
            INSERT INTO order_details 
            (order_id, menu_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$order_id, $menu_id, $qty, $item['price']]);
    }

    /* ================= CASH ================= */
    if ($payment_method === 'Cash') {
        unset($_SESSION['cart']);
        header("Location: success.php?order_id=$order_id");
        exit();
    }

    /* ================= MIDTRANS (QRIS & VA) ================= */
    if (in_array($payment_method, ['QRIS', 'VA'])) {

        require_once 'midtrans_config.php';

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order_id,
                'gross_amount' => (int)$total
            ],
            'customer_details' => [
                'first_name' => 'User-' . $_SESSION['user_id']
            ],
            'enabled_payments' => ['bank_transfer']
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $stmt = $conn->prepare("UPDATE orders SET snap_token = ? WHERE id = ?");
        $stmt->execute([$snapToken, $order_id]);

        unset($_SESSION['cart']);
        ?>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="<?= MIDTRANS_CLIENT_KEY ?>"></script>
        <script>
            snap.pay("<?= $snapToken ?>", {
                onSuccess: function () {
                    window.location = "success.php?order_id=<?= $order_id ?>";
                },
                onPending: function () {
                    window.location = "success.php?order_id=<?= $order_id ?>";
                },
                onError: function () {
                    alert("Pembayaran gagal");
                },
                onClose: function () {
                    window.location = "success.php?order_id=<?= $order_id ?>";
                }
            });
        </script>
        <?php
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .2);
        }

        /* ==== PAYMENT OPTION UI ==== */
        .payment-option {
            border: 2px solid #e0e0e0;
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all .3s ease;
            height: 100%;
            position: relative;
        }

        .payment-option:hover {
            border-color: #198754;
            transform: translateY(-5px);
        }

        .payment-option input {
            display: none;
        }

        .payment-option .check {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 22px;
            color: #198754;
            opacity: 0;
        }

        .payment-option input:checked + .check {
            opacity: 1;
        }

        .payment-option input:checked ~ .payment-icon,
        .payment-option input:checked ~ h4 {
            color: #198754;
        }

        .payment-icon {
            font-size: 56px;
            margin-bottom: 15px;
            transition: .3s;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="payment-card p-5">

                <h2 class="text-center mb-4">
                    <i class="fas fa-gem"></i> Konfirmasi Top Up Diamond
                </h2>

                <form method="POST">

                    <div class="card mb-4">
                        <div class="card-header fw-bold">
                            <i class="fa-solid fa-gamepad"></i> Data Akun Mobile Legends
                        </div>
                        <div class="card-body">
                            <input type="text" name="ml_user_id" class="form-control mb-3" placeholder="User ID" required>
                            <input type="text" name="ml_server" class="form-control" placeholder="Server" required>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <?php foreach ($_SESSION['cart'] as $menu_id => $qty):
                                $stmt = $conn->prepare("SELECT * FROM menu WHERE id=?");
                                $stmt->execute([$menu_id]);
                                $item = $stmt->fetch();
                            ?>
                                <div class="d-flex justify-content-between">
                                    <span><?= $item['name'] ?> (<?= $qty ?>x)</span>
                                    <span>Rp <?= number_format($item['price'] * $qty, 0, ',', '.') ?></span>
                                </div>
                            <?php endforeach; ?>
                            <hr>
                            <h5 class="d-flex justify-content-between">
                                <span>Total:</span>
                                <span class="text-success">Rp <?= number_format($total, 0, ',', '.') ?></span>
                            </h5>
                        </div>
                    </div>

                    <!-- PAYMENT METHOD -->
                    <div class="row g-4 mt-3">

                        <div class="col-md-4">
                            <label class="payment-option w-100">
                                <input type="radio" name="payment_method" value="Cash" required>
                                <span class="check"><i class="fas fa-check-circle"></i></span>
                                <div class="payment-icon text-success">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h4>Cash</h4>
                                <small class="text-muted">Bayar langsung</small>
                            </label>
                        </div>

                        <div class="col-md-4">
                            <label class="payment-option w-100">
                                <input type="radio" name="payment_method" value="VA" required>
                                <span class="check"><i class="fas fa-check-circle"></i></span>
                                <div class="payment-icon text-primary">
                                    <i class="fas fa-university"></i>
                                </div>
                                <h4>Virtual Account</h4>
                                <small class="text-muted">Transfer Bank</small>
                            </label>
                        </div>

                        <div class="col-md-4">
                            <label class="payment-option w-100">
                                <input type="radio" name="payment_method" value="QRIS" required>
                                <span class="check"><i class="fas fa-check-circle"></i></span>
                                <div class="payment-icon text-primary">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <h4>QRIS</h4>
                                <small class="text-muted">Scan QR</small>
                            </label>
                        </div>

                    </div>

                    <button type="submit" name="process_payment"
                            class="btn btn-success btn-lg w-100 mt-4">
                        <i class="fas fa-check-circle"></i> Proses Pembayaran
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
