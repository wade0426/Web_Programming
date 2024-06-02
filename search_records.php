<?php
    // 確保 session_start() 在任何輸出之前
    session_start();
?>

<?php
include 'db.php';
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
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
    Description: <input type="text" name="description"><br>
    排序(Sort By):
    <select name="sort_by">
        <option value="record_date">時間(Date)</option>
        <option value="amount">金額(Amount)</option>
    </select>
    Order:
    <select name="order">
        <option value="asc">升冪(Ascending)</option>
        <option value="desc">降冪(Descending)</option>
    </select><br>
    <input type="submit" value="Search">
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
        <td><?php echo $row['amount']; $total_amount += $row['amount']; ?></td>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $row['record_date']; ?></td>
        <td><?php echo $row['created_at']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<h3>總金額(Total Amount): <?php echo $total_amount; ?>&nbsp元</h3>
