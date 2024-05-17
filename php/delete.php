<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] != 1) {
    header('Location: login.php');
    exit();
}

include "connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

   
    $sql = "DELETE FROM product WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Sản phẩm đã được xóa thành công.";
    } else {
        echo "Lỗi khi xóa sản phẩm.";
    }
} else {
    echo "ID sản phẩm không hợp lệ.";
}

header('Location: index.php');
exit();
?>
