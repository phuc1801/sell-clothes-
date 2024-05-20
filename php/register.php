<?php 
    include "connect.php";
    $loi = "";

    if(isset($_POST['register'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        
        // Kiểm tra xem username đã tồn tại trong cơ sở dữ liệu chưa
        $sql_check_username = "SELECT * FROM account WHERE username = ?";
        $stmt_check_username = $conn->prepare($sql_check_username); 
        $stmt_check_username->bindParam(1, $username);
        $stmt_check_username->execute();
        
        // Đếm số hàng tìm được
        $count_username = $stmt_check_username->rowCount();

        // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
        $sql_check_email = "SELECT * FROM account WHERE email = ?";
        $stmt_check_email = $conn->prepare($sql_check_email); 
        $stmt_check_email->bindParam(1, $email);
        $stmt_check_email->execute();
        
        // Đếm số hàng tìm được
        $count_email = $stmt_check_email->rowCount();

        if($count_username > 0){
            $loi = "Username đã được sử dụng, vui lòng chọn username khác.";
        } elseif ($count_email > 0) {
            $loi = "Email đã được sử dụng, vui lòng sử dụng email khác.";
        } else {
            // Thêm tài khoản mới vào cơ sở dữ liệu
            $sql_insert = "INSERT INTO account (username, password, email) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->execute([$username, $password, $email]);

            // Hiển thị thông báo đăng ký thành công
            $loi = "Đăng ký thành công!";
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
    <title>Register</title>
</head>
<body>
   <form action="" method="post" style="width: 600px;" class="border border-primary border-2 m-auto p-2">
        <h4 class="mb-3 text-center">Đăng ký</h4>
        <?php if ($loi != ""): ?>
        <div class="alert <?php echo ($count == 0) ? 'alert-success' : 'alert-danger'; ?>"> 
            <?php echo $loi; ?>
        </div>
        <?php endif; ?>
        <div class="mb-3 ip-form">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>

            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>

            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>

            <button type="submit" class="btn btn-primary mt-3" name="register">Đăng ký</button>
            <a href="login.php" class="btn btn-secondary mt-3">Đăng nhập</a>
        </div>
   </form>
</body>
</html>
