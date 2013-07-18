<?php
header('Content-type: text/html; charset=utf-8');
session_start();

# Check if user is the master professor.
if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 0) {
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

$date_professor = date('Y-m-d');
$last_added_note = $_POST['last_added_note'];
$request_status = 4;

# Modify the row in the database.
$sql = 'UPDATE requests SET date_finished="' . $date_professor .
    '", last_added_note="' . $last_added_note . '", request_status=' . $request_status .
    ' WHERE request_id=' . $_GET['rn'] . ';';

mysql_query('SET NAMES utf8');
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

# Send a notification message to student.
if (SEND_EMAIL) {
    $sql = 'SELECT * FROM requests WHERE request_id=' . $_GET['rn'];
    $result = mysql_query($sql, $con);
    $student = mysql_fetch_assoc($result);

    if (isset($student['transfered_email']) &&
        strlen(trim($student['transfered_email'])) > 0) {
        notifyWithEmail($student['transfered_email'], 4);
    } else {
        notifyWithEmail($student['financial_assistant_email'], 4);
    }
}

mysql_close($con);

# Set feedback to 5, so the last url can display a success message.
$last_url = $_SESSION['last_url'];
$_SESSION['feedback'] = 5;
header("location: " . $last_url);

die();
?>
