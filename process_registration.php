<?php

// 包含数据库连接
include 'test_connection.php'; 

// 开启会话管理
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 提取并清理输入数据
    $accountType = isset($_POST['accountType']) ? trim($_POST['accountType']) : null;
    $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : null;
    $lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : null;

    // 验证输入
    $errors = [];

    if (empty($accountType)) {
        $errors[] = "Account type is required.";
    }

    if (empty($firstName)) {
        $errors[] = "First Name is required.";
    }

    if (empty($lastName)) {
        $errors[] = "Last Name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($confirm_password)) {
        $errors[] = "Password confirmation is required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // 如果有错误，输出并停止处理
    if (!empty($errors)) {
        echo "<h3>Registration Errors:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul><a href='register.php'>Go back to the registration page</a>";
        exit;
    }

    // 安全地哈希密码
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    echo $hashedPassword; // 注册时输出哈希以验证生成是否正常


    // 使用预处理语句插入数据以防止 SQL 注入
    $stmt = $conn->prepare("INSERT INTO UserPersonalInformation (FirstName, LastName, UserEmail, UserPassword, AccountType) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $accountType);

    if ($stmt->execute()) {
        // 自动登录：将用户信息保存到会话中
        $_SESSION['logged_in'] = true;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_first_name'] = $firstName;
        $_SESSION['user_last_name'] = $lastName;
        $_SESSION['account_type'] = $accountType;

        // 重定向到浏览页面
        header("Location: browse.php");
        exit;
    } else {
        echo "<h3>Error:</h3> " . $stmt->error . "<br><a href='register.php'>Go back to the registration page</a>";
    }

    $stmt->close();
}

// 关闭数据库连接
$conn->close();

?>
