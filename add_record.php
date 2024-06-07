<?php
// 確保 session_start() 在任何輸出之前
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="nav.css">
</head>
<?php
include 'db.php';
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_category_name']) && isset($_POST['new_category_type'])) {
        // 添加新類別
        $new_category_name = mysqli_real_escape_string($link, $_POST['new_category_name']);
        $new_category_type = mysqli_real_escape_string($link, $_POST['new_category_type']);

        $category_query = "INSERT INTO categories (name, type) VALUES ('$new_category_name', '$new_category_type')";
        if (mysqli_query($link, $category_query)) {
            echo "New category added successfully!";
        } else {
            echo "Error: " . mysqli_error($link);
        }
    } else {
        // 添加新記錄
        $user_id = $_SESSION['user_id'];
        $category_id = mysqli_real_escape_string($link, $_POST['category_id']);
        $amount = mysqli_real_escape_string($link, $_POST['amount']);
        $description = mysqli_real_escape_string($link, $_POST['description']);
        $record_date = mysqli_real_escape_string($link, $_POST['record_date']);

        $record_query = "INSERT INTO records (user_id, category_id, amount, description, record_date) 
                            VALUES ('$user_id', '$category_id', '$amount', '$description', '$record_date')";

        if (mysqli_query($link, $record_query)) {
            echo "Record added successfully!";
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
                        <p class="title">Category:</p>
                        <select name="category_id" required>
                            <?php while ($row = mysqli_fetch_assoc($categories)) : ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php endwhile; ?>
                        </select><br>
                    </div>
                </div>
                <div class="row">
                    <div class="amount">
                        <p class="title">Amount:</p>
                        <input type="number" step="1" name="amount" required><br>
                    </div>
                </div>
                <div class="row">
                    <div class="description">
                        <p class="title">Description:</p>
                        <input type="text" name="description"><br>
                    </div>
                </div>
                <div class="row">
                    <div class="record_date">
                        <p class="title">Record Date:</p>
                        <input type="date" name="record_date" required><br>
                    </div>
                </div>
                <div class="row">
                    <input type="submit" value="Add Record" class="button">
                </div>
            </form>
            <form action="" method="POST">
                <div class="row">
                    <h2>新增類別</h2>
                </div>
                <div class="row">
                    <div class="name">
                        <p class="title">Category Name:</p>
                        <input type="text" name="new_category_name" required><br>
                    </div>
                </div>
                <div class="row">
                    <div class="type">
                        <p class="title">Category Type:</p>
                        <select name="new_category_type" required>
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select><br>
                    </div>
                </div>
                <div class="row">
                    <input type="submit" value="Add Category" class="button">
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<!--  -->