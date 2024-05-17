<?php
session_start();
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    if (empty($cart)) {
        header('Location: cart.php');
        exit();
    }

    // Tính tổng tiền
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $stmt = $conn->prepare("SELECT gia FROM product WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $total += $product['gia'] * $quantity;
        }
    }

    // Thêm đơn hàng vào bảng orders
    $stmt = $conn->prepare("INSERT INTO orders (username, total) VALUES (?, ?)");
    $stmt->execute([$_SESSION['username'], $total]);
    $order_id = $conn->lastInsertId();

    // Thêm sản phẩm vào bảng order_items
    foreach ($cart as $product_id => $quantity) {
        $stmt = $conn->prepare("SELECT gia FROM product WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $product_id, $quantity, $product['gia']]);
        }
    }

    // Xóa giỏ hàng sau khi đã đặt hàng
    unset($_SESSION['cart']);

    // Chuyển hướng người dùng đến trang cảm ơn
    header('Location: thank_you.php');
    exit();
}
?>
