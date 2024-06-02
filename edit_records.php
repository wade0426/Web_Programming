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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $edit_id = mysqli_real_escape_string($link, $_POST['edit_id']);
    $category_id = mysqli_real_escape_string($link, $_POST['category_id']);
    $amount = mysqli_real_escape_string($link, $_POST['amount']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $record_date = mysqli_real_escape_string($link, $_POST['record_date']);

    $update_query = "UPDATE records 
                     SET category_id='$category_id', amount='$amount', description='$description', record_date='$record_date' 
                     WHERE id='$edit_id' AND user_id='{$_SESSION['user_id']}'";
    
    if (mysqli_query($link, $update_query)) {
        echo "Record updated successfully!";
    } else {
        echo "Error: " . mysqli_error($link);
    }
}

if (isset($_GET['edit_id'])) {
    $edit_id = mysqli_real_escape_string($link, $_GET['edit_id']);
    $record_query = "SELECT * FROM records WHERE id='$edit_id' AND user_id='{$_SESSION['user_id']}'";
    $record_result = mysqli_query($link, $record_query);
    $record = mysqli_fetch_assoc($record_result);

    $category_query = "SELECT * FROM categories";
    $categories = mysqli_query($link, $category_query);
}

?>

<?php if (isset($record)) : ?>
    <h2>Edit Record</h2>
    <form method="POST" action="">
        <input type="hidden" name="edit_id" value="<?php echo $record['id']; ?>">
        Category: 
        <select name="category_id" required>
            <?php while ($row = mysqli_fetch_assoc($categories)) : ?>
                <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $record['category_id']) echo 'selected'; ?>>
                    <?php echo $row['name']; ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        Amount: <input type="number" step="0.01" name="amount" value="<?php echo $record['amount']; ?>" required><br>
        Description: <input type="text" name="description" value="<?php echo $record['description']; ?>"><br>
        Record Date: <input type="date" name="record_date" value="<?php echo $record['record_date']; ?>" required><br>
        <input type="submit" value="Update Record">
    </form>
<?php else : ?>
    <p>No record found to edit.</p>
<?php endif; ?>
