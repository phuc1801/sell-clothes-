<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] != 1) {
    header('Location: login.php');
    exit();
}

include "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && isset($_GET['email'])) {
    $order_id = intval($_GET['id']);
    $customer_email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

    if (!$customer_email) {
        die("Địa chỉ email không hợp lệ.");
    }

    // Cập nhật trạng thái của đơn hàng thành 'approved'
    $sql = "UPDATE orders SET status = 'approved' WHERE id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    // Gửi email xác nhận
    if (sendMail($customer_email)) {
        // Chuyển hướng người dùng trở lại trang danh sách đơn hàng chờ duyệt
        header('Location: pending_orders.php');
        exit();
    } else {
        echo "Gửi email thất bại.";
    }
} else {
    // Nếu không có thông tin đơn hàng được cung cấp, chuyển hướng người dùng trở lại trang chính
    header('Location: index.php');
    exit();
}

function sendMail($email) {
    require "../PHPMailer-master/src/PHPMailer.php";
    require "../PHPMailer-master/src/SMTP.php";
    require '../PHPMailer-master/src/Exception.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true); // true: enables exceptions
    try {
        $mail->SMTPDebug = 0; // Đặt chế độ debug để xem log chi tiết
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->Host = 'smtp.gmail.com'; // SMTP servers
        $mail->SMTPAuth = true; // Enable authentication
        $mail->Username = 'phucnd.wordpress@gmail.com'; // SMTP username
        $mail->Password = 'wkvj dvfr xxjf otgj'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // encryption TLS/SSL
        $mail->Port = 465; // port to connect to
        $mail->setFrom('phucnd.wordpress@gmail.com', 'Nguyen Duy Phuc');
        $mail->addAddress($email);
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Xác nhận đơn hàng';
        $mail->Body = "Đơn hàng của bạn đã được xác nhận";
        $mail->smtpConnect(array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        ));
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo 'Error: ', $mail->ErrorInfo;
        return false;
    }
}
?>
