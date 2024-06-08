<?php
// 確保 session_start() 在任何輸出之前
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>主頁</title>
  <link rel="stylesheet" href="view_records.css">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"> -->
</head>
<style>
</style>
<?php
include 'db.php';
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
  include 'show_error.php';
  die("Please login first.");
}

$user_id = $_SESSION['user_id'];
$query = "SELECT records.*, categories.name AS category_name 
          FROM records 
          JOIN categories ON records.category_id = categories.id 
          WHERE user_id='$user_id'
          ORDER BY record_date DESC";

$records = mysqli_query($link, $query);
?>

<body>
  <!-- 使用引用方式(停止使用) -->
  <!-- <ul class="nav c_center">
        <li><a href="view_records.php" class="menu">主頁</a></li>
        <li><a href="add_record.php" class="menu">新增</a></li>
        <li><a href="search_records.php" class="menu">查詢</a></li>
        <li><a href="#" class="menu">幫助</a></li>
        <li><a href="logout.php" class="menu">登出</a></li>
        <li><a href="#" class="menu">Laptop</a></li>
    </ul> -->
  <h1 style="text-align:center;">主頁</h1>

  <br>
  <hr><br>

  <!-- 日曆 -->
  <div class="calendar">
    <div class="calendar-header">
      <button id="prev-month">&#8249;</button>
      <div>
        <select id="year-select"></select>
        <select id="month-select"></select>
      </div>
      <button id="next-month">&#8250;</button>
    </div>
    <div class="calendar-grid">
      <div>S</div>
      <div>M</div>
      <div>T</div>
      <div>W</div>
      <div>T</div>
      <div>F</div>
      <div>S</div>
    </div>
    <div class="date-info"></div>
  </div>

  <script>
    const today = new Date();
    let currentYear = today.getFullYear();
    let currentMonth = today.getMonth();

    const yearSelect = document.getElementById('year-select');
    const monthSelect = document.getElementById('month-select');
    const calendarGrid = document.querySelector('.calendar-grid');
    const dateInfo = document.querySelector('.date-info');

    // 填入年份選項
    for (let year = currentYear - 10; year <= currentYear + 10; year++) {
      const option = document.createElement('option');
      option.value = year;
      option.text = year;
      yearSelect.add(option);
    }

    // 填入月份選項
    const months = ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
    for (let i = 0; i < months.length; i++) {
      const option = document.createElement('option');
      option.value = i;
      option.text = months[i];
      monthSelect.add(option);
    }

    // 設定初始年份和月份
    yearSelect.value = currentYear;
    monthSelect.value = currentMonth;

    // 渲染月曆
    renderCalendar(currentYear, currentMonth);

    // 監聽年份和月份變更
    yearSelect.addEventListener('change', updateCalendar);
    monthSelect.addEventListener('change', updateCalendar);

    // 監聽上一個月和下一個月按鈕
    document.getElementById('prev-month').addEventListener('click', prevMonth);
    document.getElementById('next-month').addEventListener('click', nextMonth);

    function renderCalendar(year, month) {
      const daysInMonth = new Date(year, month + 1, 0).getDate();
      const firstDayOfMonth = new Date(year, month, 1).getDay();

      calendarGrid.innerHTML = '';

      // 渲染星期
      const weekdays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
      for (let i = 0; i < 7; i++) {
        const weekday = document.createElement('div');
        weekday.textContent = weekdays[i];
        calendarGrid.appendChild(weekday);
      }

      // 渲染日期格子
      let date = 1;
      for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 7; j++) {
          if (i === 0 && j < firstDayOfMonth) {
            calendarGrid.appendChild(document.createElement('div'));
          } else if (date > daysInMonth) {
            break;
          } else {
            const day = document.createElement('div');
            day.textContent = date;
            day.addEventListener('click', selectDate);

            // 今天的日期自動被點擊
            if (year === today.getFullYear() && month === today.getMonth() && date === today.getDate()) {
              day.classList.add('selected');
              dateInfo.textContent = `${year}年${month+1}月${date}日`;
            }

            calendarGrid.appendChild(day);
            date++;
          }
        }
      }
    }

    function updateCalendar() {
      const selectedYear = parseInt(yearSelect.value);
      const selectedMonth = parseInt(monthSelect.value);
      currentYear = selectedYear;
      currentMonth = selectedMonth;
      renderCalendar(selectedYear, selectedMonth);
      dateInfo.textContent = '';
    }

    function selectDate(event) {
      const selectedDate = event.target;
      const allDates = calendarGrid.querySelectorAll('div:not(:nth-child(-n+7))');
      allDates.forEach(date => date.classList.remove('selected'));
      selectedDate.classList.add('selected');

      const year = currentYear;
      const month = currentMonth + 1;
      const day = selectedDate.textContent;
      const formattedDate = `${year}年${month}月${day}日`;
      dateInfo.textContent = formattedDate;
      console.log(formattedDate); //顯示選擇的日期
    }

    function prevMonth() {
      currentMonth--;
      if (currentMonth < 0) {
        currentYear--;
        currentMonth = 11;
      }
      yearSelect.value = currentYear;
      monthSelect.value = currentMonth;
      renderCalendar(currentYear, currentMonth);
      dateInfo.textContent = '';
    }

    function nextMonth() {
      currentMonth++;
      if (currentMonth > 11) {
        currentYear++;
        currentMonth = 0;
      }
      yearSelect.value = currentYear;
      monthSelect.value = currentMonth;
      renderCalendar(currentYear, currentMonth);
      dateInfo.textContent = '';
    }
  </script>

  <!-- 日曆 -->

  <br>
  <hr><br>

  <?php /* 註解未啟用 舊版紀錄顯示
    <table border="1"  align="center" valign="middle" style="text-align:center;">
        <tr>
            <th>類別(Category)</th>
            <th>金額(Amount)</th>
            <th>描述(Description)</th>
            <th>日期(Record Date)</th>
            <th>建立日期(Created At)</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($records)) : ?>
        <tr>
            <td><?php echo $row['category_name']; ?></td>
            <td><?php echo $row['amount']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['record_date']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    */ ?>

  <!--  -->

  <?php

  // include 'db.php';

  // if (!isset($_SESSION['user_id'])) {
  //     die("Please login first.");
  // }

  $user_id = $_SESSION['user_id'];

  // 刪除全部
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_all'])) {
    $delete_all_query = "DELETE FROM records WHERE user_id='$user_id'";

    if (mysqli_query($link, $delete_all_query)) {
      echo "All records deleted successfully!";
    } else {
      echo "Error: " . mysqli_error($link);
    }
  }

  // 選擇刪除
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_selected'])) {
    $delete_ids = implode(",", $_POST['delete_ids']);
    $delete_selected_query = "DELETE FROM records WHERE id IN ($delete_ids) AND user_id='$user_id'";

    if (mysqli_query($link, $delete_selected_query)) {
      echo "Selected records deleted successfully!";
    } else {
      echo "Error: " . mysqli_error($link);
    }
  }

  $query = "SELECT records.*, categories.name AS category_name 
              FROM records 
              JOIN categories ON records.category_id = categories.id 
              WHERE user_id='$user_id'
              ORDER BY record_date DESC";

  $records = mysqli_query($link, $query);
  ?>

  <form method="POST" action="">
    <input type="submit" name="delete_all" value="Delete All Records" onclick="return confirm('Are you sure you want to delete all records?')">
  </form>

  <form method="POST" action="">
    <table border="1">
      <tr>
        <th>勾選</th>
        <th>類別(Category)</th>
        <th>金額(Amount)</th>
        <th>描述(Description)</th>
        <th>日期(Record Date)</th>
        <th>創建時間(Created At)</th>
        <th>動作</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($records)) : ?>
        <tr>
          <td><input type="checkbox" name="delete_ids[]" value="<?php echo $row['id']; ?>"></td>
          <td><?php echo $row['category_name']; ?></td>
          <td><?php echo $row['amount']; ?></td>
          <td><?php echo $row['description']; ?></td>
          <td><?php echo $row['record_date']; ?></td>
          <td><?php echo $row['created_at']; ?></td>
          <td>
            <a href="edit_records.php?edit_id=<?php echo $row['id']; ?>">Edit</a>
            <!-- 刪除改用勾選 -->
            <!-- <a href="view_records.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a> -->
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
    <input type="submit" name="delete_selected" value="刪除所選紀錄" onclick="return confirm('Are you sure you want to delete selected records?')">
  </form>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script> -->
</body>

</html>

<!-- -->

<?php
