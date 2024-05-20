<?php
session_start();
include"connect.php";
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT username, password, type FROM account WHERE username = '$username'";
    $stmt = $conn->prepare($sql);
    $query = $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $userreal = $result['username'];
        $passreal = $result['password'];
        $type = $result['type'];
        if ($username == $userreal && $password == $passreal) {
            if($type == 1){
                $_SESSION['username'] = $username;
                $_SESSION['type'] = $type;
                header('Location: index.php');
                exit;
            }else{
                $_SESSION['username'] = $username;
                $_SESSION['type'] = $type;
                header('Location: user.php');
                exit;
            }
        } else {
            $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
        }
    }else{
        $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css?v= <?php echo time();?>">
    <link rel="stylesheet" href="../css/reset.css">

    <title>Document</title>
</head>
<body>
    <div class="login-box">
    <h2>Login</h2>
    <form method="post" action="login.php">
        <div class="user-box">
            <input type="text" name="username" required=""> 
            <label>Username</label>
        </div>
        <div class="user-box">
            <input type="password" name="password" required=""> 
            <label>Password</label>
        </div>
        <p>Bạn chưa có tài khoản <a href="register.php">Đăng Kí</a></p>
        <a href="forgot_password.php">Quên mật khẩu</a>
        <button class="btn" type="submit" name="submit" style="width: 150px;">Đăng Nhập</button> 
        
    </form>
    <?php if(isset($error)) { ?> 
        <p><?php echo $error; ?></p>
    <?php } ?>
    </div>
    
   
</body>
</html>
