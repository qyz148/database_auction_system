<?php
// 包含数据库连接脚本
include 'test_connection.php';

// 启动会话
session_start();

// 检查用户是否已登录
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo '<div class="text-center">Logged in successfully. Please wait for redirecting...</div>';
    header("refresh:1;url=index.php");
    exit();
}

// 检查是否提供了POST数据
if (isset($_POST['email']) && isset($_POST['password'])) {
    // 获取并清理输入
    $userEmail = trim($_POST['email']);
    $userPassword = trim($_POST['password']); // 表单中的明文密码

    // 准备SQL查询
    $stmt = $conn->prepare("SELECT * FROM userpersonalinformation WHERE UserEmail = ?");
    $stmt->bind_param("s", $userEmail); // 将电子邮件作为字符串参数绑定
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // 获取用户数据
        $row = $result->fetch_assoc();

        // 直接比较明文密码
        if ($userPassword === $row['UserPassword']) {
            // 登录成功
            $_SESSION['logged_in']= true;
            $_SESSION['email'] = $row['UserEmail'];
            $_SESSION['account_type'] = $row['AccountType'];
            $_SESSION['user_id'] = $row['UserID'];
            echo '<div class="text-center">Logged in successfully. Please wait for redirecting...</div>';
            header("refresh:1;url=index.php");
        } else {
            // 密码不匹配
            echo '<div class="text-center">Invalid password。</div>';
            header("refresh:1;url=index.php");
        }
    } else {
        // 未找到电子邮件
        echo '<div class="text-center">Invalid email。</div>';
        header("refresh:1;url=index.php");
    }

    $stmt->close(); // 关闭语句
} else {
    // 缺少POST数据
    echo '<div class="text-center">Please provide email and password。</div>';
    header("refresh:1;url=index.php");
}

// 关闭数据库连接
$conn->close();
?>

