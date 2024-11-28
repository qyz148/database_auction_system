<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.
include 'test_connection.php'; // Include database connection

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
        echo "</ul>Go back to the registration page";
        exit;
    }

    $sql = "INSERT INTO UserPersonalInformation (FirstName, LastName, UserEmail, UserPassword, AccountType)
            VALUES ('$firstName', '$lastName', '$email', '$password', '$accountType')";

    if ($conn->query($sql) === TRUE) {
        // 自动登录：将用户信息保存到会话中
        $_SESSION['user_email'] = $email;
        $_SESSION['user_first_name'] = $firstName;
        $_SESSION['user_last_name'] = $lastName;

        // 重定向到主菜单页面
        header("Location: login_result.php");
        exit;
    } else {
        echo "<h3>Error:</h3> " . $conn->error . "<br>Go back to the registration page";
    }
}

// 关闭数据库连接
$conn->close();

?>