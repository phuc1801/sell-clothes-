<?php
session_start();
include "connect.php";

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$products = [];
if (!empty($cart)) {
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT * FROM product WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/cart.css">
    <title>Basket</title>
</head>
<body>
    <main>
        <div class="basket">
            <div class="basket-labels">
                <ul>
                    <li class="item item-heading">Sản phẩm</li>
                    <li class="price">Giá</li>
                    <li class="quantity">Số lượng</li>
                    <li class="subtotal">Tổng tiền</li>
                </ul>
            </div>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="basket-product">
                        <div class="item">
                            <div class="product-image">
                                <img src="../img/<?php echo htmlspecialchars($product['anh']); ?>" alt="<?php echo htmlspecialchars($product['ten']); ?>" class="product-frame">
                            </div>
                            <div class="product-details">
                                <h1><?php echo htmlspecialchars($product['ten']); ?></h1>
                                <p>Bảo hành <?php echo htmlspecialchars($product['baohanh']); ?> tháng</p>
                            </div>
                        </div>
                        <div class="price"><?php echo number_format($product['gia'], 0, ',', '.'); ?>đ</div>
                        <div class="quantity">
                            <form action="update_quantity.php" method="post">
                                <input type="number" name="quantity" value="<?php echo $cart[$product['id']]; ?>" min="1" class="quantity-field">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit">Cập nhật</button>
                            </form>
                        </div>
                        <div class="subtotal"><?php echo number_format($product['gia'] * $cart[$product['id']], 0, ',', '.'); ?>đ</div>
                        <div class="remove">
                            <form action="remove_from_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Giỏ hàng của bạn đang trống.</p>
            <?php endif; ?>
        </div>
        <aside>
            <div class="summary">
                <div class="summary-total-items"><span class="total-items"></span> Tổng tiền</div>
                <div class="customer-info">
                    <label for="fullname">Họ và tên:</label>
                    <input type="text" id="fullname" name="fullname" required>
                    <br> <br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <br> <br>
                    <label for="phone">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="summary-total">
                    <div class="total-title">Total</div>
                    <div class="total-value final-value" id="basket-total">
                        <?php
                        $total = 0;
                        foreach ($products as $product) {
                            $total += $product['gia'] * $cart[$product['id']];
                        }
                        echo number_format($total, 0, ',', '.') . 'đ';
                        ?>
                    </div>
                </div>
                <div class="summary-checkout">
                    <form action="checkout.php" method="post">
                        <button type="submit" class="checkout-cta">Go to Secure Checkout</button>
                    </form>
                </div>
            </div>
        </aside>
    </main>
</body>
</html>
