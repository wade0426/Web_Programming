<?php
// 確保 session_start() 在任何輸出之前
session_start();
?>

<?php
include 'db.php';
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    include 'show_alert.php';
    show_error();
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];
$search_query = "SELECT records.*, categories.name AS category_name FROM records JOIN categories ON records.category_id = categories.id WHERE user_id='$user_id'";
$conditions = [];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
        $start_date = mysqli_real_escape_string($link, $_GET['start_date']);
        $conditions[] = "record_date >= '$start_date'";
    }
    if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
        $end_date = mysqli_real_escape_string($link, $_GET['end_date']);
        $conditions[] = "record_date <= '$end_date'";
    }
    if (isset($_GET['description']) && !empty($_GET['description'])) {
        $description = mysqli_real_escape_string($link, $_GET['description']);
        $conditions[] = "description LIKE '%$description%'";
    }
    if (!empty($conditions)) {
        $search_query .= " AND " . implode(' AND ', $conditions);
    }
    // 類別搜尋
    // is_numeric 判斷是否為數字
    if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
        print_r($_GET);
        $category_id = mysqli_real_escape_string($link, $_GET['category_id']);
        $search_query .= " AND category_id = $category_id";
        print_r($search_query);
    }
    if (isset($_GET['sort_by']) && in_array($_GET['sort_by'], ['record_date', 'amount'])) {
        $sort_by = $_GET['sort_by'];
        $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
        $search_query .= " ORDER BY $sort_by $order";
    }
}

$records = mysqli_query($link, $search_query);
$total_amount = 0;
?>

<h2>查詢紀錄</h2>
<form method="GET" action="">
    開始日期: <input type="date" name="start_date"><br>
    結束日期: <input type="date" name="end_date"><br>
    描述(Description): <input type="text" name="description"><br>
    類別(Category)：
    <select name="category_id">
        <option value="all" <?php if (isset($_GET['category_id']) && $_GET['category_id'] === 'all') echo 'selected'; ?>>全部</option>
        <?php
        $category_query = "SELECT * FROM categories";
        $categories = mysqli_query($link, $category_query);
        while ($row = mysqli_fetch_assoc($categories)) : ?>
            <?php
            // 保留使用者搜尋設定
            if (isset($_GET['category_id']) && $_GET['category_id'] === $row['id']) {
                echo '<option value=' . $row['id'] . ' selected>' . $row['name'] . '</option>';
            }
            else {
                echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
            }
            ?>
        <?php endwhile; ?>
    </select>
    <br>
    排序(Sort By):
    <select name="sort_by">
        <!-- <option value="record_date">時間(Date)</option> -->
        <!-- <option value="amount">金額(Amount)</option> -->
        <!-- 保留使用者搜尋設定 -->
        <option value="record_date" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] === 'record_date') echo 'selected'; ?>>時間(Date)</option>
        <option value="amount" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] === 'amount') echo 'selected'; ?>>金額(Amount)</option>
    </select>
    排序(Order)：
    <select name="order">
        <!-- <option value="asc">升冪(Ascending)</option>
        <option value="desc">降冪(Descending)</option> -->
        <!-- 保留使用者搜尋設定 -->
        <option value="asc" <?php if (isset($_GET['order']) && $_GET['order'] === 'asc') echo 'selected'; ?>>升冪(Ascending)</option>
        <option value="desc" <?php if (isset($_GET['order']) && $_GET['order'] === 'desc') echo 'selected'; ?>>降冪(Descending)</option>
    </select><br>
    <input type="submit" value="搜尋">
</form>

<table border="1">
    <tr>
        <th>類別(Category)</th>
        <th>金額(Amount)</th>
        <th>描述(Description)</th>
        <th>日期(Record Date)</th>
        <th>創建時間(Created At)</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($records)) : ?>
        <tr>
            <td><?php echo $row['category_name']; ?></td>
            <td><?php echo $row['amount'];
                $total_amount += $row['amount']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['record_date']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<h3>總金額(Total Amount): <?php echo $total_amount; ?>&nbsp元</h3>