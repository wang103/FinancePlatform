<?php
session_start();

if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 0) {
    die();
}

require('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

$qry = 'UPDATE announcements SET title="' . $_POST['title'] . '", content="' .
    $_POST['content'] . '", poster="' . $_SESSION['LAST_NAME'] .
    $_SESSION['FIRST_NAME'] . '", date=NOW() WHERE announce_id=' . $_GET['an'];

mysql_query('SET NAMES utf8');
if (!mysql_query($qry, $con)) {
    die('Error: ' . mysql_error());
}

mysql_close($con);

header('location: ../index.php');
die();
?>
