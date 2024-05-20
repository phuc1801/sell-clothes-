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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Chỉnh Sửa Sản Phẩm</h2>
        <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Tên sản phẩm:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row['ten']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh:</label>
                <div class="row">
                    <div class="" style="width: 80px; height: 80px; margin-left: 10px;">
                        <img src="../img/<?php echo htmlspecialchars($row['anh']); ?>" alt="" class="img-thumbnail" style="width: 100%;">
                    </div>
                    <div class="col-md-9">
                        <input type="file" class="form-control-file" id="image" name="hinhanh">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="baohanh">Bảo hành:</label>
                <input type="text" class="form-control" id="baohanh" name="baohanh" value="<?php echo htmlspecialchars($row['baohanh']); ?>" required>
            </div>
            <div class="form-group">
                <label for="trangthai">Trạng thái:</label>
                <input type="text" class="form-control" id="trangthai" name="trangthai" value="<?php echo htmlspecialchars($row['trangthai']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gia">Giá:</label>
                <input type="text" class="form-control" id="gia" name="gia" value="<?php echo htmlspecialchars($row['gia']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="btn">Cập Nhật</button>
        </form>
    </div>
</body>
</html>

