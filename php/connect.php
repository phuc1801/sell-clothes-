<?php
$conn = new PDO('mysql:host=localhost;dbname=qlbh', 'root' ,'');
if ($conn) {
    echo "Connected successfully";
}else{
    echo "Connection failed";
}
?>