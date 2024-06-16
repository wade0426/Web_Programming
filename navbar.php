<?php
// session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/nav.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="jf_navbar">
        <ul class="nav c_center">
            <li>
                <a href="view_records.php" class="menu">主頁</a>
            </li>
            <li>
                <a href="add_record.php" class="menu">新增</a>
            </li>
            <li>
                <a href="search_records.php" class="menu">查詢</a>
            </li>
            <!-- <li> -->
                <!-- <a href="#" class="menu">幫助</a> -->
            <!-- </li> -->
            <?php if (isset($_SESSION['user_id'])) : ?>
                <!-- 登入才會有管理系統 -->
                <li><a href="manage.php" class="menu">管理系統</a></li>
                <li><a href="logout.php" class="menu">登出</a></li>
            <?php else : ?>
                <li><a href="login.php" class="menu">登入</a></li>
                <!-- <li><a href="register.php" class="menu">Register</a></li> -->
            <?php endif; ?>
        </ul>
        <a href="#!" class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </a>
    </div>
</body>
<script>
$(document).ready(function(){
    $('.menu-toggle').on('click',function(){
        $(this).toggleClass('active');
        $('.jf_navbar .nav').toggleClass('active');
    })
    $('.nav-link').on('click',function(){
        $('.menu-toggle').removeClass('active');
        $('.jf_navbar .nav').removeClass('active');
    })
})
</script>
