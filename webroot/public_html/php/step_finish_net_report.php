<?php
header('Content-type: text/html; charset=utf-8');
session_start();

# Check if user is the master professor.
if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 0) {
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
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style01.css'>
<script src='../js/interface_listener.js'></script>
<script src='../js/validate_form.js'></script>
</head>

<body>

<form id="net_report_form" name="net_report_form" action="finish_net_report.php" 
onsubmit="return validateSubmitRequestForm('net_report_form')" method="post">

<?php
require(dirname(__FILE__) . '/common_interface_01.php');
?>

<button type="submit" name="submit_button" value=0>不同意此申请</button>
<button type="submit" name="submit_button" value=1>保存</button>
<button type="submit" name="submit_button" value=2>保存并完成网报</button>
<input action="action" type="button" onclick="history.go(-1);" value="返回"/>

</form>

<script>
// The elements in the common interface are all disabled, need to
// enable all of them.
var f = document.forms['net_report_form'];
for (var i = 0, fLen = f.length; i < fLen; i++) {
    f.elements[i].readOnly = false;
    f.elements[i].disabled = false;
}

document.getElementsByName("id")[0].readOnly = true;
document.getElementsByName("finance_assist_name")[0].readOnly = true;
document.getElementsByName("date")[0].readOnly = true;
document.getElementsByName("date1")[0].readOnly = true;
document.getElementsByName("date2")[0].readOnly = true;
document.getElementsByName("date3")[0].readOnly = true;
document.getElementsByName("date4")[0].readOnly = true;
document.getElementsByName("special")[0].disabled = true;
document.getElementsByName("special")[1].disabled = true;

var intel_value = f['special_input_1'].value;
var asset_value = f['special_input_2'].value;

// Must set the initial value for int_rule.
if ((intel_value == null || intel_value == "") &&
    (asset_value == null || asset_value == "")) {
    int_rule = -1;
} else if (intel_value == null || intel_value == "") {
    int_rule = 1;
} else if (asset_value == null || asset_value == "") {
    int_rule = 2;
} else {
    int_rule = 0;
}

</script>

</body>

</html>
