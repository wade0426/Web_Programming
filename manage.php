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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

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

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);

                    if (password_verify($password, $row['password'])) {
                        // 和普通登入不同，這裡是驗證登入，所以要改 session 變數
                        $_SESSION['v_user_id'] = $row['id'];
                        $_SESSION['v_username'] = $row['username'];
                        // 
                        // echo "Login successful!";
                        // 託管不支援
                        // header('Location: view_records.php');
                        echo "<script>alert('驗證成功！')</script>";
                        echo "<script>
                        window.location.href = 'manage.php';</script>";
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
        echo "";
    } else {
        echo '<form method="POST" action="">
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">驗證分份</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input name="username" class="form-control" id="floatingInput">
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

    <button type="button" class="btn btn-warning" name="change_password">變更密碼</button>
    <button type="button" class="btn btn-danger" name="deleter_user">刪除帳號</button>
</body>

</html>