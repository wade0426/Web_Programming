<?php
// 確保 session_start() 在任何輸出之前
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增紀錄</title>
    <link rel="stylesheet" href="CSS/nav.css">
</head>
<?php
include 'db.php';
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    include 'show_alert.php';
    show_error();
    show_message_danger("請先登入!!! ", "登入", "login.php");
    // die("Please login first.");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 判斷是新增類別還是新增記錄
    if (isset($_POST['new_category_name']) && isset($_POST['new_category_type'])) {
        // 添加新類別
        $new_category_name = mysqli_real_escape_string($link, $_POST['new_category_name']);
        $new_category_type = mysqli_real_escape_string($link, $_POST['new_category_type']);

        $category_query = "INSERT INTO categories (name, type) VALUES ('$new_category_name', '$new_category_type')";
        if (mysqli_query($link, $category_query)) {
            // echo "New category added successfully!";
            include 'show_alert.php';
            show_toasts_success("成功新增類別！");
        } else {
            echo "Error: " . mysqli_error($link);
        }
    } else {
        // 添加新記錄
        $user_id = $_SESSION['user_id'];
        // mysqli_real_escape_string() 防止 SQL 注入攻擊
        $category_id = mysqli_real_escape_string($link, $_POST['category_id']);
        $amount = mysqli_real_escape_string($link, $_POST['amount']);
        $description = mysqli_real_escape_string($link, $_POST['description']);
        $record_date = mysqli_real_escape_string($link, $_POST['record_date']);
        // 測試用
        // print_r($_POST);
        // 去看資料庫 SELECT * FROM `categories` WHERE `id` = $_POST[category_id] => 是 expense 還是 income;
        $category_id = mysqli_real_escape_string($link, $_POST['category_id']);
        $category_query = "SELECT * FROM categories WHERE id = '$category_id'";
        $category_result = mysqli_query($link, $category_query);
        $category = mysqli_fetch_assoc($category_result);
        // 判斷是支出還是收入 支出加負號
        if ($category['type'] == 'expense') {
            $amount = -abs($amount);
        } else {
            $amount = abs($amount);
        }
        $record_query = "INSERT INTO records (user_id, category_id, amount, description, record_date) 
                            VALUES ('$user_id', '$category_id', '$amount', '$description', '$record_date')";

        if (mysqli_query($link, $record_query)) {
            include 'show_alert.php';
            show_toasts_success("成功新增一筆紀錄！");
            // echo "Record added successfully!";
        } else {
            echo "Error: " . mysqli_error($link);
        }
    }
}
$category_query = "SELECT * FROM categories";
$categories = mysqli_query($link, $category_query);
?>

<body>
    <!-- 停止使用(使用引入) -->
    <!-- <ul class="nav">
        <li><a href="view_records.php" class="menu">主頁</a></li>
        <li><a href="add_record.php" class="menu">新增</a></li>
        <li><a href="#" class="menu">幫助</a></li>
        <li><a href="logout.php" class="menu">登出</a></li>
    </ul> -->
    <div class="container">
        <div class="from">
            <div class="row">
                <h2>新增消費紀錄</h2>
            </div>
            <!-- 請記得不要刪form表單!! -->
            <form action="" method="POST">
                <div class="row">
                    <div class="category">
                        <p class="title">類別：</p>
                        <select name="category_id" required>
                            <?php while ($row = mysqli_fetch_assoc($categories)) : ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php endwhile; ?>
                        </select><br>
                    </div>
                </div>
                <div class="row">
                    <div class="amount">
                        <p class="title">金額：</p>
                        <input type="number" step="1" name="amount" min="0" required></input><br>
                    </div>
                </div>
                <div class="row">
                    <div class="description">
                        <p class="title">描述：</p>
                        <input type="text" name="description"><br>
                    </div>
                </div>
                <div class="row">
                    <div class="record_date">
                        <p class="title">時間：</p>
                        <!-- 設定預設值為今天的日期 -->
                        <input type="date" name="record_date" value="<?php echo date('Y-m-d'); ?>" required><br>
                    </div>
                </div>
                <div class="row">
                    <input type="submit" value="新增紀錄" class="button">
                </div>
            </form>
            <form action="" method="POST">
                <div class="row">
                    <h2>新增類別</h2>
                </div>
                <div class="row">
                    <div class="name">
                        <p class="title">類別名稱：</p>
                        <input type="text" name="new_category_name" required><br>
                    </div>
                </div>
                <div class="row">
                    <div class="type">
                        <p class="title">類別類型：</p>
                        <select name="new_category_type" required>
                            <option value="expense">支出</option>
                            <option value="income">收入</option>
                        </select><br>
                    </div>
                </div>
                <div class="row">
                    <input type="submit" value="新增類別" class="button">
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<!--  -->