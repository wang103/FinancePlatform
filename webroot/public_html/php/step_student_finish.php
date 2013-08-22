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

# Check for user name.
if ($_SESSION['USERNAME'] != $row['financial_assistant_username'] &&
    $_SESSION['USERNAME'] != $row['transfered_username']) {
    echo 'error code: 1';
    die();
}
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style01.css'>
<script src='../js/interface_listener.js'></script>
</head>

<body>

<?php
require(dirname(__FILE__) . '/common_interface_01.php');
?>

<input action="action" type="button" onclick='<?php echo 'location.href="student_finish.php?rn=' . $row['request_id'] . '"'?>' value="完成报销"/>
<input action="action" type="button" onclick="history.go(-1);" value="返回"/>

</body>

</html>
