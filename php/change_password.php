<?php 
    include "connect.php";
    $loi = "";

    if(isset($_POST['send'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $new_password = $_POST['new_password'];
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM account WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $stmt->execute();
        
        // Đếm số hàng tìm được
        $count = $stmt->rowCount();
        if($count == 0){
            $loi = "Username hoặc mật khẩu không đúng";
        } else {
            // Cập nhật mật khẩu mới trong cơ sở dữ liệu
            $sql = "UPDATE account SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$new_password, $username]);

            // Hiển thị thông báo mật khẩu mới đã được cập nhật
            $loi = "Mật khẩu mới đã được cập nhật thành công";
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
    <title>Change Password</title>
</head>
<body>
   <form action="" method="post" style="width: 600px;" class="border border-primary border-2 m-auto p-2">
        <h4 class="mb-3 text-center">Đổi mật khẩu</h4>
        <?php if ($loi != ""): ?>
        <div class="alert <?php echo ($count == 0) ? 'alert-danger' : 'alert-success'; ?>">
            <?php echo $loi; ?>
        </div>
        <?php endif; ?>
        <div class="mb-3 ip-form">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>

            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>

            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>

            <button type="submit" class="btn btn-primary mt-3" name="send">Đổi mật khẩu</button>
            <a href="user.php" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
   </form>
</body>
</html>
