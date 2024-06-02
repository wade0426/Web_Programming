<?php
    // 確保 session_start() 在任何輸出之前
    session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
    <?php
        include 'db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['login'])){
                $username = mysqli_real_escape_string($link, $_POST['username']);
                $password = $_POST['password'];

                $query = "SELECT * FROM users WHERE username='$username'";
                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);

                    if (password_verify($password, $row['password'])) {
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['username'] = $row['username'];
                        // echo "Login successful!";
                        // 託管不支援
                        // header('Location: view_records.php');
                        echo "<script>
                            window.location.href = 'view_records.php';</script>";
                        exit();
                    }
                    else {
                        echo "<script>alert('登入失敗！(Invalid password.)')</script>";

                    }
                }
                else {
                    echo "<script>
                        alert('登入失敗！(No user found.)');</script>";
                        // window.location.href = 'login.php';</script>";
                }
            }
        }

        // Register
        if (isset($_POST['register'])){
            $username = mysqli_real_escape_string($link, $_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = mysqli_real_escape_string($link, $_POST['email']);

            // 檢查用戶名是否已經存在
            $check_query = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($link, $check_query);
            if (mysqli_num_rows($result) > 0) {
                echo "<script>
                        alert('錯誤: 用戶名 " . $username . " 已經存在。請選擇另一個名稱。');
                        window.location.href = 'login.php';</script>";
                exit();
            }
            // 檢查email是否已經存在
            $check_query = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($link, $check_query);
            if (mysqli_num_rows($result) > 0) {
                echo "<script>
                    alert('錯誤: " . $email . " 已經存在。請選擇另一個信箱。');
                    window.location.href = 'login.php';</script>";
                exit();
            }

            $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
            if (mysqli_query($link, $query)) {
                echo "<script>alert('註冊成功！(Registration successful)')</script>";
                echo "<script>alert('請再次登入')</script>";
                // echo "Registration successful!";
            } else {
                // echo "Error: " . mysqli_error($link);
                echo "<script>alert('Error: ')</script>";
            }
        }
        mysqli_close($link);
    ?>

    <!-- 舊版登入畫面 -->
    <!-- <form method="POST" action="">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form> -->
    
    <div class="wrapper">
        <div class="card-switch">
            <label class="switch">
                <input type="checkbox" class="toggle">
                <span class="slider"></span>
                <span class="card-side"></span>
                <div class="flip-card__inner">
                    <div class="flip-card__front">
                        <div class="title">登入</div>
                            <form class="flip-card__form" method="POST" action="">
                                <!-- login -->
                                <input class="flip-card__input" name="username" placeholder="UserName" type="text" required>
                                <input class="flip-card__input" name="password" placeholder="Password" type="password" required>
                                <button type="submit" class="flip-card__btn" name="login">登入</button>
                            </form>
                        </div>
                    <div class="flip-card__back">
                        <div class="title">註冊</div>
                            <form class="flip-card__form" method="POST" action="">
                                <!-- Register  -->
                                <input class="flip-card__input" placeholder="UserName" type="name" name="username" required>
                                <input class="flip-card__input" name="password" placeholder="Password" type="password" required>
                                <input class="flip-card__input" name="email" placeholder="Email" type="email" required>
                                <button class="flip-card__btn" name="register">註冊</button>
                            </form>
                        </div>
                    </div>
            </label>
        </div>
    </div>
</body>
</html>