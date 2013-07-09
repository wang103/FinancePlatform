<?php
session_start();

# Check user signed in.
if (!isset($_SESSION['EMAIL']) || empty($_SESSION['EMAIL'])) {
    echo 'error code: 0';
    die();
}

# Connect to the database.
require_once('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load request details.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM requests WHERE request_id=' . $_GET['rn']);
$row = mysql_fetch_array($result);

# Check for user name.
if ($_SESSION['EMAIL'] != $row['submitter_email']) {
    echo 'error code: 1';
    die();
}

$request_status = 2;

# Modify the row in the database.
$sql = 'UPDATE requests SET request_status=' . $request_status .
    ' WHERE request_id=' . $_GET['rn'] . ';';

mysql_query('SET NAMES utf8');
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

mysql_close($con);

# Send a notification message to student.


# Set status to 4, so the last url can display a success message.
$last_url = $_SESSION['last_url'];
header("location: " . $last_url . "?status=4");

die();
?>
