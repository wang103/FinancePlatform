<?php
header('Content-type: text/html; charset=utf-8');
session_start();

# Check if student.
if (!isset($_SESSION['STATUS']) ||
    ($_SESSION['STATUS'] != 1 && $_SESSION['STATUS'] != 2)) {
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

# Check if user is the financial assistant.
if ($row['financial_assistant_email'] != $_SESSION['EMAIL']) {
    mysql_close($con);

    echo 'error code: 1';
    die();
}

$request_status = 7;

# Modify the row in the database.
$sql = 'UPDATE requests SET request_status=' . $request_status .
    ' WHERE request_id=' . $_GET['rn'] . ';';

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

# Send a notification message to student.
if (SEND_EMAIL) {
    $sql = 'SELECT * FROM advisors WHERE student_email="' . $_SESSION['EMAIL'] . '"';
    $result = mysql_query($sql, $con);
    $advisor_prof = mysql_fetch_assoc($result);
    notifyWithEmail($advisor_prof['advisor_email'], 9);

    if ($row['request_status'] == 1) {
        $sql = 'SELECT * FROM users WHERE status=0';
        $result = mysql_query($sql, $con);
        $master_prof = mysql_fetch_assoc($result);
     
        notifyWithEmail($master_prof['email'], 9);
    }
}

mysql_close($con);

# Set session var to 9 so the last url can display a success message.
$last_url = $_SESSION['last_url'];
$_SESSION['feedback'] = 9;
header("location: " . $last_url);

die();
?>
