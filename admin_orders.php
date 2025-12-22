<?php
require_once 'config.php';

// Cek akses admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// --- PROSES GANTI STATUS ---
if(isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $order_id = $_POST['order_id'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if($stmt->execute([$status, $order_id])) {
        header("Location: admin_orders.php");
        exit();
    } else {
        echo "<script>alert('Gagal mengubah status!');</script>";
    }
}
// ---------------------------

// Ambil data order + user
$orders = $conn->query("
    SELECT o.*, u.full_name 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Pesanan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .select-status { font-weight: bold; cursor: pointer; }
        .status-Pending { background-color: #fff3cd; color: #856404; }
        .status-Process { background-color: #cce5ff; color: #004085; }
        .status-Completed { background-color: #d4edda; color: #155724; }
        .status-Cancelled { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">
        <a href="admin_dashboard.php" class="text-dark text-decoration-none">
            <i class="fas fa-arrow-left"></i>
        </a>
        Daftar Pesanan Masuk
    </h2>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan & Akun ML</th>
                        <th style="width: 30%;">Diamond Pesanan</th>
                        <th>Total & Metode</th>
                        <th>Status Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($orders->rowCount() > 0): ?>
                    <?php while($order = $orders->fetch()): ?>
                    <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>

                        <!-- 🔥 PELANGGAN + ML ID -->
                        <td>
                            <div class="fw-bold"><?php echo $order['full_name']; ?></div>

                            <div class="small text-muted">
                                🆔 ML ID: <strong><?php echo htmlspecialchars($order['ml_user_id']); ?></strong><br>
                                🌐 Server: <strong><?php echo htmlspecialchars($order['ml_server']); ?></strong>
                            </div>

                            <span class="badge bg-secondary mt-1">
                                <?php echo $order['order_type']; ?>
                            </span>

                            <div class="small text-muted">
                                <?php echo date('d M Y H:i', strtotime($order['order_date'])); ?>
                            </div>
                        </td>

                        <!-- DETAIL DIAMOND -->
                        <td>
                            <ul class="list-unstyled mb-0 small">
                                <?php
                                $stmt = $conn->prepare("
                                    SELECT d.*, m.name 
                                    FROM order_details d 
                                    JOIN menu m ON d.menu_id = m.id 
                                    WHERE d.order_id = ?
                                ");
                                $stmt->execute([$order['id']]);
                                while($item = $stmt->fetch()){
                                    echo "<li class='border-bottom py-1'>
                                        <span class='fw-bold'>{$item['quantity']}x</span> {$item['name']}
                                    </li>";
                                }
                                ?>
                            </ul>
                        </td>

                        <!-- TOTAL & METODE -->
                        <td>
                            <div class="fw-bold text-success">
                                Rp <?php echo number_format($order['total_amount'],0,',','.'); ?>
                            </div>
                            <?php if($order['payment_method'] == 'QRIS'): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-qrcode"></i> QRIS
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-money-bill-wave"></i> Cash
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- STATUS -->
                        <td>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="update_status" value="1">

                                <select name="status"
                                    class="form-select select-status status-<?php echo $order['status']; ?>"
                                    onchange="this.form.submit()">
                                    <option value="Pending" <?php if($order['status']=='Pending') echo 'selected'; ?>>🟡 Pending</option>
                                    <option value="Process" <?php if($order['status']=='Process') echo 'selected'; ?>>🔵 Process</option>
                                    <option value="Completed" <?php if($order['status']=='Completed') echo 'selected'; ?>>🟢 Completed</option>
                                    <option value="Cancelled" <?php if($order['status']=='Cancelled') echo 'selected'; ?>>🔴 Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
