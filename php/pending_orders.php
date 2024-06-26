<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] != 1) {
    header('Location: login.php');
    exit();
}

include "connect.php";

$sql = "SELECT orders.*, account.email FROM orders JOIN account ON orders.username = account.username WHERE orders.status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Danh sách đơn hàng chờ duyệt</h1>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID Đơn hàng</th>
                    <th>Tên khách hàng</th>
                    <th>Email</th>
                    <th>Tổng tiền</th>
                    <th>Thời gian đặt hàng</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo number_format($row['total'], 0, ',', '.') . 'đ'; ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <a href="approve_order.php?id=<?php echo $row['id']; ?>&email=<?php echo htmlspecialchars($row['email']); ?>" class="btn btn-success btn-sm" onclick="return confirm('Bạn có chắc chắn muốn duyệt đơn hàng này?');">Duyệt</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary mt-3">Quay lại trang chính</a>
    </div>
</body>
</html>
