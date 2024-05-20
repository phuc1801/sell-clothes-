<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] != 1) {
    header('Location: login.php');
    exit();
}

include "connect.php";


$sql = "SELECT * FROM product";
$stmt = $conn->prepare($sql);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/search.css?v=<?php echo time();?>">
    <link rel="stylesheet" href="../css/reset.css">

    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="heading">
            <div class="heading-item">
                <p class="heading-sp">SẢN PHẨM NỔI BẬT *</p>
            </div>
            <div class="heading-tg"></div>
        </div>
        <div class="content-box">
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="box-item">
                        <div class="item-img width50">
                            <img src="../img/<?php echo htmlspecialchars($row['anh']); ?>" alt="" />
                        </div>
                        <div class="item-content width50">
                            <p><?php echo htmlspecialchars($row['ten']); ?></p>
                            <ul>
                                <li>Bảo hành: <?php echo htmlspecialchars($row['baohanh']); ?> tháng</li>
                                <li>Trạng thái: <?php echo htmlspecialchars($row['trangthai']); ?></li>
                            </ul>
                        </div>
                        <div class="item-button width50">
                            <?php                          
                            if (is_numeric($row['gia'])) {
                                $formatted_price = number_format($row['gia'], 0, ',', '.');
                                echo "<button>Giá {$formatted_price}đ</button>";
                            } else {
                                echo "<button>Giá không hợp lệ</button>";
                            }
                            ?>
                            
                            
                                             
                            
                            <a href="edit.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn sửa sản phẩm này?');" class="btn-a"> <button>Sửa</button></a>

                            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" class="btn-a"> <button>Xóa</button></a>
                           
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "Không có sản phẩm nào.";
            }
            ?>
        </div>
    </div>
    <div class="btn-f">
        <form action="logout.php" method="post">
            <button type="submit" class="btn-black">Đăng xuất</button>
        </form>
        <form action="add_product.php"method="post">
        <button type="submit" class="btn-black">Thêm</button>
        </form>
        <form action="pending_orders.php"method="post">
        <button type="submit" class="btn-black">Duyệt đơn hàng</button>
        </form>
    </div> 
</body>
</html>
