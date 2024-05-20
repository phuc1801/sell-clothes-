<?php 
    include "connect.php";
    $loi = "";

    if(isset($_POST['send'])){
        $email = $_POST['email'];
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM account WHERE email = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(1, $email);
        $stmt->execute();
        
        // Đếm số hàng tìm được
        $count = $stmt->rowCount();
        if($count == 0){
            $loi = "Email của bạn chưa đăng ký thành viên";
        } else {
            // Tạo một token reset mật khẩu ngẫu nhiên với độ dài 6 ký tự
            $token = substr(md5(rand(0, 999999)), 0, 6);  

            // Cập nhật mật khẩu mới trong cơ sở dữ liệu
            $sql = "UPDATE account SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$token, $email]);
            sendMail($email, $token); // Thêm dấu chấm phẩy ở đây

            // Hiển thị thông báo mật khẩu mới đã được cập nhật
            $loi = "Mật khẩu mới của bạn đã được gửi tới email của bạn";
        }
    }
?>

<?php 
    function sendMail($email, $token){
        require "../PHPMailer-master/src/PHPMailer.php"; 
        require "../PHPMailer-master/src/SMTP.php"; 
        require '../PHPMailer-master/src/Exception.php'; 
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);//true:enables exceptions
        try {
            $mail->SMTPDebug = 0; //0,1,2: chế độ debug
            $mail->isSMTP();  
            $mail->CharSet  = "utf-8";
            $mail->Host = 'smtp.gmail.com';  //SMTP servers
            $mail->SMTPAuth = true; // Enable authentication
            $mail->Username = 'phucnd.wordpress@gmail.com'; // SMTP username
            $mail->Password = 'wkvj dvfr xxjf otgj';   // SMTP password
            $mail->SMTPSecure = 'ssl';  // encryption TLS/SSL 
            $mail->Port = 465;  // port to connect to                
            $mail->setFrom('phucnd.wordpress@gmail.com', 'Nguyen Duy Phuc'); 
            $mail->addAddress($email); 
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'Reset Password';
            $noidungthu = "Mật khẩu mới của bạn là: {$token}"; 
            $mail->Body = $noidungthu;
            $mail->smtpConnect( array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                )
            ));
            $mail->send();
            echo 'Đã gửi mail xong';
        } catch (Exception $e) {
            echo 'Error: ', $mail->ErrorInfo;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/forgot.css?v=<?php echo time(); ?>">
    <title>Forgot</title>
</head>
<body>
   <form action="" method="post" style="width: 600px;" class="border border-primary border-2 m-auto p-2">
        <h4 class="mb-3 text-center">Quên mật khẩu</h4>
        <?php if ($loi != ""): ?>
        <div class="alert <?php echo ($count == 0) ? 'alert-danger' : 'alert-success'; ?>">
            <?php echo $loi; ?>
        </div>
        <?php endif; ?>
        <div class="mb-3 ip-form">
            <label for="email" class="form-label">Nhập Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <button type="submit" class="btn btn-primary mt-3" name="send">Gửi yêu cầu</button>
            <a href="login.php" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
   </form>
</body>
</html>
