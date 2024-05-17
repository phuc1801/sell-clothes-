<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] != 1) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $order_id = $_GET['id'];

    include "connect.php";

    // Cập nhật trạng thái của đơn hàng thành 'approved'
    $sql = "UPDATE orders SET status = 'approved' WHERE id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    // Chuyển hướng người dùng trở lại trang danh sách đơn hàng chờ duyệt
    header('Location: pending_orders.php');
    exit();
} else {
    // Nếu không có thông tin đơn hàng được cung cấp, chuyển hướng người dùng trở lại trang chính
    header('Location: index.php');
    exit();
}
?>
