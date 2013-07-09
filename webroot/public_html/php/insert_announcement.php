<?php
session_start();

# Check if user is professor.
if (!isset($_SESSION['STATUS']) ||
    ($_SESSION['STATUS'] != 0 && $_SESSION['STATUS'] != 3)) {
    die();
}

# Connect to the database.
require_once('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Insert new announcement into the database.
$sql = 'INSERT INTO announcements (title, content, poster, date) VALUES ("' .
    $_POST['title'] . '", "' . $_POST['content'] . '", "' . $_SESSION['LAST_NAME'] .
    $_SESSION['FIRST_NAME'] . '", NOW())';

mysql_query('SET NAMES utf8');
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

mysql_close($con);

header('location: ../index.php');
die();
?>
