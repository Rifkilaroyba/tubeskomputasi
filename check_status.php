<?php
require_once 'config.php';
require_once 'midtrans_config.php';

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    die('Order ID tidak valid');
}

$order_code = 'ORDER-' . $order_id;

try {
    // Ambil status dari Midtrans
    $result = \Midtrans\Transaction::status($order_code);

    /**
     * Normalisasi response:
     * Midtrans bisa return object ATAU array
     */
    /** @var object $status */
    $status = is_array($result)
        ? json_decode(json_encode($result))
        : $result;

    // Pastikan properti ada
    if (!isset($status->transaction_status)) {
        throw new Exception('Invalid response from Midtrans');
    }

    if (in_array($status->transaction_status, ['settlement', 'capture'], true)) {

        // Update database
        $stmt = $conn->prepare("
            UPDATE orders 
            SET payment_status = 'success'
            WHERE id = ?
        ");
        $stmt->execute([$order_id]);
        ?>
        <script>
            alert("✅ Pembayaran berhasil!");
            window.location.href = "dashboard.php";
        </script>
        <?php
        exit();

    } elseif ($status->transaction_status === 'pending') {
        ?>
        <script>
            alert("⏳ Pembayaran masih menunggu.");
            window.location.href = "payment.php";
        </script>
        <?php
        exit();

    } else {
        ?>
        <script>
            alert("❌ Pembayaran gagal atau dibatalkan.");
            window.location.href = "dashboard.php";
        </script>
        <?php
        exit();
    }

} catch (Exception $e) {
    ?>
    <script>
        alert("❌ Gagal cek status pembayaran");
        window.location.href = "dashboard.php";
    </script>
    <?php
    exit();
}
