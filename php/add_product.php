<?php
    include "connect.php";  
   
    if(isset($_POST['btn'])){
        $name = $_POST['name'];
        $img = $_FILES['hinhanh']['name'];
        $img_tmp_name = $_FILES['hinhanh']['tmp_name'];
        $baohanh = $_POST['baohanh'];
        $trangthai = $_POST['trangthai'];
        $gia = $_POST['gia'];
        $sql = "insert into product(ten, anh, baohanh, trangthai, gia)
        values('$name', '$img', '$baohanh', '$trangthai', '$gia')
        ";
        $stmt = $conn->prepare($sql);
        $query = $stmt->execute();
        $upload_directory = '../img/';
        if($query){
            if (move_uploaded_file($img_tmp_name, $upload_directory . $img)) {
                echo "Thêm thành công!";
            } else {
                echo "Lỗi khi di chuyển tệp tải lên!";
            }
        }else{
            echo "Thêm thất bại, vui lòng thử lại!";
        }
        echo $upload_directory ;
    }
    if(isset($_POST['back'])){
        header('Location: index.php');
        exit();
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <p>name</p>
        <input type="text" name="name">
        <p>image</p>
        <input type="file" name="hinhanh">
        <p>Bảo hành</p>
        <input type="text" name="baohanh">
        <p>Trạng thái</p>
        <input type="text" name="trangthai">
        <p>Giá</p>
        <input type="text" name="gia"> 
        <br><br> 
        <button type="submit" name="btn">Submit</button>
        <button type="submit" name="back">Quay lại</button>

    </form>
</body>
</html>