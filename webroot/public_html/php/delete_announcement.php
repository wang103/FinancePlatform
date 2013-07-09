<?php
session_start();

if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 0) {
    die();
}

require_once('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Remove the announcement from the table.
$announce_id = $_GET['an'];

$qry = 'DELETE FROM announcements WHERE announce_id="' . $announce_id . '"';
mysql_query($qry);

mysql_close($con);

header('location: ../index.php');
die();
?>
