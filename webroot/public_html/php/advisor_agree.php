<?php
session_start();

# Check if user is a advisor professor.
if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 3) {
    die();
}

# Connect to the database.
require_once('../../config.php');
require_once('utils.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load request details.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM requests WHERE request_id=' . $_GET['rn']);
$row = mysql_fetch_array($result);

mysql_close($con);

# Check if user is the advisor.
if (!isMyStudentsSubmission($row['submitter_email'], $_SESSION['EMAIL'])) {
    echo 'error code: 0';
    die();
}

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

$agreed_date = date('Y-m-d');
$request_status = 1;

# Modify the row in the database.
mysql_query('SET NAMES utf8');
$sql = 'UPDATE requests SET date_advisor_agreed="' . $agreed_date .
    '", request_status=' . $request_status . ' WHERE request_id=' .
    $_GET['rn'] . ';';

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

# Send a notification message to student.
if (SEND_EMAIL) {
    $sql = 'SELECT * FROM users WHERE status=0';
    $result = mysql_query($sql, $con);
    $master_prof = mysql_fetch_assoc($result);
    notifyWithEmail($master_prof['email'], 1);
}

mysql_close($con);

# Set feedback to 6, so the last url can display a success message.
$last_url = $_SESSION['last_url'];
$_SESSION['feedback'] = 6;
header("location: " . $last_url);

die();
?>
