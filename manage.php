<?php
// 確保 session_start() 在任何輸出之前
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>帳號管理系統</title>
    <link rel="stylesheet" href="CSS/manage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<style>
</style>

<body>
    <?php
    include 'db.php'; //引入資料庫連線檔案
    include 'navbar.php'; //引入導覽列
    ?>

    <?php
    // 如果沒有登入，就導向登入頁面
    if (!isset($_SESSION['user_id'])) {
        echo "<script>
            window.location.href = 'login.php';</script>";
    }

    if (!isset($_SESSION['v_user_id'])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = mysqli_real_escape_string($link, $_POST['username']);
                $password = $_POST['password'];

                $query = "SELECT * FROM users WHERE username='$username'";
                $result = mysqli_query($link, $query);
                // 防止登入帳號和驗證帳號不同
                if (mysqli_num_rows($result) == 1 && $_SESSION['username'] == $_POST['username']) {
                    $row = mysqli_fetch_assoc($result);

                    if (password_verify($password, $row['password'])) {
                        // 和普通登入不同，這裡是驗證登入，所以要改 session 變數
                        $_SESSION['v_user_id'] = $row['id'];
                        $_SESSION['v_username'] = $row['username'];
                        // 
                        // echo "Login successful!";
                        // 託管不支援
                        // header('Location: view_records.php');
                        // echo "<script>alert('驗證成功！')</script>";
                        include 'show_alert.php';
                        show_toasts_success("驗證成功！");
                        // echo "<script>
                        // window.location.href = 'manage.php';</script>";
                        // exit();
                    } else {
                        echo "<script>alert('驗證失敗！')</script>";
                    }
                } else {
                    echo "<script>
                        alert('驗證失敗!!!');</script>";
                    // window.location.href = 'login.php';</script>";
                }
            }
        }
    }
    ?>

    <?php
    if (isset($_SESSION['v_user_id'])) {
        // 已驗證
        // SQL 查詢語法
        $query = "SELECT * FROM users WHERE id=" . $_SESSION['v_user_id'];
        // 執行 SQL 查詢
        $result = mysqli_query($link, $query);
        // 取得第一筆資料
        $row = mysqli_fetch_assoc($result); // 取得第一筆資料
        // 顯示資料
        echo "<h1 class='jf_title'>帳號管理系統</h1>";
        echo "<div class='container'>";
        // echo "<h2>歡迎 " . $row['username'] . " 回來！</h2>";
        echo "<h3>您的使用者名稱是：" . $row['username'] . "</h3>";
        echo "<h3>您的 email 是：" . $row['email'] . "</h3>";
        echo "<h3>註冊時間：" . $row['created_at'] . "</h3>";
        // 顯示按鈕
        echo '<form action="" method="POST">
                    <button type="submit" class="btn btn-warning" name="change_password">變更密碼</button>
                    
                    <button name="deleter_user" type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">刪除帳號</button>
            </form>';
        echo "</div>";
    } else {
        echo '
        <form method="POST" action="">
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">驗證身份</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input name="username" class="form-control" id="floatingInput" placeholder="Leave a comment here">
                                <label for="floatingInput">請輸入帳號：</label>
                            </div>
                            <div class="form-floating">
                                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                                <label for="floatingPassword">請輸入密碼：</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                            <!-- <button type="button" class="btn btn-primary">驗證</button> -->
                            <!-- <button type="submit" class="btn btn-primary mb-3">Confirm identity</button> -->
                            <div class="col-7">
                                <button class="btn btn-primary" type="submit">驗證</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <script>
        // 當網頁的 DOM 元素載入完成後，執行以下程式碼
        window.addEventListener("DOMContentLoaded", function() {
            // 建立一個新的 Modal 物件，並指定要顯示的元素為 "staticBackdrop"
            var modal = new bootstrap.Modal(document.getElementById("staticBackdrop"));
            modal.show();
        });
    </script>';
    } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- 顯示按鈕 -->
    <!-- <form action="" method="POST">
        <button type="submit" class="btn btn-warning" name="change_password">變更密碼</button>
        <button type="submit" class="btn btn-danger" name="deleter_user">刪除帳號</button>
    </form> -->

    <?php
    // 變更密碼按鈕
    if (isset($_POST['change_password'])) {
        echo "<div class='container'>";
        echo '
        <form action="" method="POST">
            <div class="form-floating mb-3">
                <input name="old_password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">請輸入原本的密碼：</label>
            </div>
            <div class="form-floating mb-3">
                <input name="new_password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">請輸入要更改的密碼：</label>
            </div>
            <div class="form-floating mb-3">
                <input name="v_new_password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">請再次輸入要更改的密碼：</label>
            </div>
            <div class="col-auto">
                <button name="submit_new_password" class="btn btn-primary" type="submit">更改密碼</button>
            </div>
        </form>';
        echo "</div>";
    }
    if (isset($_POST['deleter_user'])) {
        echo"<div class='container'>";    
        // echo "刪除帳號";
        echo "<p style='font-weight: bolder; color:red'>你確定要刪除帳號嗎？(此動作無法復原)</p>";
        // include 'show_alert.php';
        // show_error();

        // 輸入密碼確認身分
        // 跳出輸入密碼的視窗
        echo '
        <form action="" method="POST">
            <div class="form-floating mb-3">
                <input name="del_password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">請輸入密碼：</label>
            </div>
            <div class="col-auto">
                <button name="submit_delete_user" class="btn btn-danger" type="submit">刪除帳號</button>
            </div>
        </form>';
        echo "</div>";
    }

    if (isset($_POST['submit_delete_user'])) {
        // 確認密碼
        if (isset($_POST['del_password'])) {
            $query = "SELECT * FROM users WHERE id=" . $_SESSION['v_user_id'];
            $result = mysqli_query($link, $query);
            $row = mysqli_fetch_assoc($result);
            if (password_verify($_POST['del_password'], $row['password'])) {
                // 如果這個帳號還有紀錄，也要一併刪除
                // 刪除紀錄
                $query = "DELETE FROM records WHERE user_id=" . $_SESSION['v_user_id'];
                mysqli_query($link, $query);
                // 刪除使用者
                $query = "DELETE FROM users WHERE id=" . $_SESSION['v_user_id'];
                echo $query;
                if (mysqli_query($link, $query)) {
                    unset($_SESSION['v_user_id']);
                    unset($_SESSION['v_username']);
                    echo "<script>alert('帳號刪除成功！')</script>";
                    echo "<script>
                    window.location.href = 'logout.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('帳號刪除失敗！')</script>";
                }
            } else {
                echo "<script>alert('密碼輸入錯誤！')</script>";
            }
        }
    }

    // 更改密碼
    if (isset($_POST['submit_new_password'])) {
        // 先確定有沒有輸入舊密碼和新密碼
        if (isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['v_new_password'])) {
            // 確認新密碼和再次輸入的新密碼是否相同
            if ($_POST['new_password'] == $_POST['v_new_password']) {
                // 確認舊密碼是否正確
                $query = "SELECT * FROM users WHERE id=" . $_SESSION['v_user_id'];
                $result = mysqli_query($link, $query);
                $row = mysqli_fetch_assoc($result);
                if (password_verify($_POST['old_password'], $row['password'])) {
                    // 更新密碼
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $query = "UPDATE users SET password='$new_password' WHERE id=" . $_SESSION['v_user_id'];
                    if (mysqli_query($link, $query)) {
                        echo "<script>alert('密碼更新成功！')</script>";
                    } else {
                        echo "<script>alert('密碼更新失敗！')</script>";
                    }
                } else {
                    echo "<script>alert('舊密碼輸入錯誤！')</script>";
                }
            } else {
                echo "<script>alert('新密碼和再次輸入的新密碼不相同！')</script>";
            }
        }
    }
    ?>
</body>

</html>