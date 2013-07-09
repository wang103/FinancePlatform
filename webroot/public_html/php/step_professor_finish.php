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

# Load request details.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM requests WHERE request_id=' . $_GET['rn']);
$row = mysql_fetch_array($result);

$assistants = mysql_query('SELECT * FROM users WHERE status=1 OR status=2');

mysql_close($con);
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style01.css'>
<script src='../js/interface_listener.js'></script>
</head>

<body>

<form name="add_last_note_form" action=<?php echo "professor_finish.php?rn=" . $row['request_id']?> method="post">
<label id="last_added_note_label">添加教师意见（若无意见请直接点完成）：</label> <br>
<textarea id="last_added_note_content" name="last_added_note" rows="6" cols="60"></textarea>
<br>
<input type="submit" value="完成">
<input action="action" type="button" onclick="history.go(-1);" value="返回"/>
</form>

<hr>

<?php
require('common_interface_01.php');
?>

</body>

</html>
