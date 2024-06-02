<?php
// session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nav.css">
</head>
<ul class="nav c_center">
    <li><a href="view_records.php" class="menu">主頁</a></li>
    <li><a href="add_record.php" class="menu">新增</a></li>
    <li><a href="search_records.php" class="menu">查詢</a></li>
    <!-- <li><a href="edit_records.php" class="menu">編輯</a></li> -->
    <li><a href="#" class="menu">幫助</a></li>
    <!-- <li><a href="logout.php" class="menu">登出</a></li> -->
    <!-- <li><a href="#" class="menu">Laptop</a></li> -->

    <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="logout.php" class="menu">登出</a></li>
    <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    <?php endif; ?>
</ul>