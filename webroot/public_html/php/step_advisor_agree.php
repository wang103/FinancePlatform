<?php
header('Content-type: text/html; charset=utf-8');
session_start();

# Check if user is a advisor professor.
if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 3) {
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

$assistants = mysql_query('SELECT * FROM users WHERE status=1 OR status=2');

mysql_close($con);

# Check if user is the advisor.
if (!isMyStudentsSubmission($row['financial_assistant_username'], $_SESSION['USERNAME'])) {
    echo 'error code: 0';
    die();
}
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style01.css'>
<script src='../js/interface_listener.js'></script>
</head>

<body>

<form id="advisor_agree_form" name="advisor_agree_form" action=<?php echo "advisor_agree.php?rn=" . $_GET['rn']?>
onsubmit="return validateSubmitRequestForm('advisor_agree_form')" method="post">

<?php
require(dirname(__FILE__) . '/common_interface_01.php');
?>

<button type="submit" name="submit_button" value=1>不同意此申请</button>
<button type="submit" name="submit_button" value=2>同意此申请</button>
<input action="action" type="button" onclick="history.go(-1);" value="返回"/>

</form>

</body>

</html>
