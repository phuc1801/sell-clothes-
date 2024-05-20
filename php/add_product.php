<?php
include "connect.php";  

if(isset($_POST['btn'])){
    $name = $_POST['name'];
    $img = $_FILES['hinhanh']['name'];
    $img_tmp_name = $_FILES['hinhanh']['tmp_name'];
    $baohanh = $_POST['baohanh'];
    $trangthai = $_POST['trangthai'];
    $gia = $_POST['gia'];
    $sql = "INSERT INTO product(ten, anh, baohanh, trangthai, gia)
    VALUES('$name', '$img', '$baohanh', '$trangthai', '$gia')";
    $stmt = $conn->prepare($sql);
    $query = $stmt->execute();
    $upload_directory = '../img/';
    if($query){
        if (move_uploaded_file($img_tmp_name, $upload_directory . $img)) {
            echo '<div class="alert alert-success" role="alert">Thêm thành công!</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Lỗi khi di chuyển tệp tải lên!</div>';
        }
    }else{
        echo '<div class="alert alert-danger" role="alert">Thêm thất bại, vui lòng thử lại!</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Thêm Sản Phẩm Mới</h2>
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Tên sản phẩm:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="hinhanh">Hình ảnh:</label>
                <input type="file" class="form-control-file" id="hinhanh" name="hinhanh" required>
            </div>
            <div class="form-group">
                <label for="baohanh">Bảo hành:</label>
                <input type="text" class="form-control" id="baohanh" name="baohanh" required>
            </div>
            <div class="form-group">
                <label for="trangthai">Trạng thái:</label>
                <input type="text" class="form-control" id="trangthai" name="trangthai" required>
            </div>
            <div class="form-group">
                <label for="gia">Giá:</label>
                <input type="text" class="form-control" id="gia" name="gia" required>
            </div>
            <button type="submit" class="btn btn-primary" name="btn">Submit</button>
            <a href="index.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
