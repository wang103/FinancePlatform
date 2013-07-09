<?php
session_start();

# Check if user is the master professor.
if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 0) {
    die();
}

# Connect to the database.
require_once('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

$last_added_note = $_POST['last_added_note'];
$request_status = 4;

# Modify the row in the database.
$sql = 'UPDATE requests SET last_added_note="' . $last_added_note . '", request_status=' . $request_status .
    ' WHERE request_id=' . $_GET['rn'] . ';';

mysql_query('SET NAMES utf8');
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

mysql_close($con);

# Send a notification message to student.


# Set status to 5, so the last url can display a success message.
$last_url = $_SESSION['last_url'];
header("location: " . $last_url . "?status=5");

die();
?>
