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

# Modify the announcement from the table.
$announce_id = $_GET['an'];

mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM announcements WHERE announce_id=' . $announce_id);
$row = mysql_fetch_array($result);

mysql_close($con);

echo '
    <form action="modify_announcement.php?an=' . $announce_id .
    '" method="post">
    <p>
    修改公告：<br>
    主题：<br>
    <input type="text" name="title" required value="' . $row['title'] . '">
    <br>
    内容：<br>
    <textarea name="content" rows="6" cols="60" required>' .
    $row['content'] .
    '</textarea>
    <br>
    <input type="submit" value="完成修改"/>
    <br>
    </p>
    </form>';
?>
