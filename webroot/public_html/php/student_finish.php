<?php
header('Content-type: text/html; charset=utf-8');
session_start();

# Check user signed in.
if (!isset($_SESSION['USERNAME']) || empty($_SESSION['USERNAME'])) {
    echo 'error code: 0';
    die();
}

# Connect to the database.
require_once(dirname(__FILE__) . '/../../config.php');
require_once(dirname(__FILE__) . '/utils.php');

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
if ($_SESSION['USERNAME'] != $row['financial_assistant_username'] &&
    $_SESSION['USERNAME'] != $row['transfered_username']) {
    echo 'error code: 1';
    die();
}

$date_student = date('Y-m-d');
$request_status = 3;

# Modify the row in the database.
$sql = 'UPDATE requests SET date_student_finished="' .
    $date_student . '", request_status=' . $request_status .
    ' WHERE request_id=' . $_GET['rn'] . ';';

mysql_query('SET NAMES utf8');
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

# Send a notification message to student.
if (SEND_EMAIL) {
    $sql = 'SELECT * FROM users WHERE status=0';
    $result = mysql_query($sql, $con);
    $master_prof = mysql_fetch_assoc($result);
    notifyWithEmail($master_prof['email'], 3);
}

mysql_close($con);

# Set feedback to 4, so the last url can display a success message.
$last_url = $_SESSION['last_url'];
$_SESSION['feedback'] = 4;
header("location: " . $last_url);

die();
?>
