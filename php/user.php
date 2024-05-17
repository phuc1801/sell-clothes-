<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] != 0) {
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
    <link rel="stylesheet" href="../css/button.css">
    <title>Sản phẩm nổi bật</title>
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
                            
                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit">Add to cart</button>
                            </form>
                            
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
            <button type="submit">Đăng xuất</button>
        </form>
    </div>
</body>
</html>
