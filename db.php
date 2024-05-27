<?php
$link = mysqli_connect("localhost", "root", "", "accounting");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
