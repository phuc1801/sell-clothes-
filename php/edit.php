<?php 
include "connect.php";

// Lấy id từ GET parameter và kiểm tra
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID không hợp lệ.");
}

// Chuẩn bị và thực thi câu lệnh SQL an toàn
$sql = "SELECT * FROM product WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Không tìm thấy sản phẩm.");
}

echo $id;

if (isset($_POST['btn'])) {
    $name = $_POST['name'];
    $baohanh = $_POST['baohanh'];
    $trangthai = $_POST['trangthai'];
    $gia = $_POST['gia'];
    $img = $_FILES['hinhanh']['name'];
    $img_tmp_name = $_FILES['hinhanh']['tmp_name'];

    // Nếu có file ảnh mới được upload, xử lý việc upload file
    if ($img) {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($img);
        // Kiểm tra file hợp lệ (ví dụ: đúng loại ảnh, kích thước phù hợp)
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $valid_extensions) && move_uploaded_file($img_tmp_name, $target_file)) {
            // Update câu lệnh SQL với ảnh mới
            $sql1 = "UPDATE product SET ten = :ten, anh = :anh, baohanh = :baohanh, trangthai = :trangthai, gia = :gia WHERE id = :id";
            $stmt = $conn->prepare($sql1);
            $stmt->bindParam(':ten', $name);
            $stmt->bindParam(':anh', $img);
            $stmt->bindParam(':baohanh', $baohanh);
            $stmt->bindParam(':trangthai', $trangthai);
            $stmt->bindParam(':gia', $gia);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            echo "Lỗi khi upload ảnh.";
        }
    } else {
        // Update câu lệnh SQL mà không thay đổi ảnh
        $sql1 = "UPDATE product SET ten = :ten, baohanh = :baohanh, trangthai = :trangthai, gia = :gia WHERE id = :id";
        $stmt = $conn->prepare($sql1);
        $stmt->bindParam(':ten', $name);
        $stmt->bindParam(':baohanh', $baohanh);
        $stmt->bindParam(':trangthai', $trangthai);
        $stmt->bindParam(':gia', $gia);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <p>Name</p>
        <input type="text" name="name" value="<?php echo htmlspecialchars($row['ten']); ?>">
        <p>Image</p>
        <span><img src="../img/<?php echo htmlspecialchars($row['anh']); ?>" alt="" width="50px" height="50px"></span>
        <br> <br>
        <input type="file" name="hinhanh">
        <p>Bảo hành</p>
        <input type="text" name="baohanh" value="<?php echo htmlspecialchars($row['baohanh']); ?>">
        <p>Trạng thái</p>
        <input type="text" name="trangthai" value="<?php echo htmlspecialchars($row['trangthai']); ?>">
        <p>Giá</p>
        <input type="text" name="gia" value="<?php echo htmlspecialchars($row['gia']); ?>">
        <br><br>
        <button type="submit" name="btn">Edit</button>
    </form>
</body>
</html>
