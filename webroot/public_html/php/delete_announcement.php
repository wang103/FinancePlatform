<?php
session_start();

# Check if user is professor.
if (!isset($_SESSION['STATUS']) ||
    ($_SESSION['STATUS'] != 0 && $_SESSION['STATUS'] != 3)) {
    die();
}

# Connect to the database.
require_once(dirname(__FILE__) . '/../../config.php');

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
