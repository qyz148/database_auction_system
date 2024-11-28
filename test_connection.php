<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 调试输出，确认代码是否执行
echo "Starting database connection test...<br>";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auction_system";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>