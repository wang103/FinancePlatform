<?php
header('Content-type: text/html; charset=utf-8');
session_start();

# Check user signed in.
if (!isset($_SESSION['EMAIL']) || empty($_SESSION['EMAIL'])) {
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

$assistants = mysql_query('SELECT * FROM users WHERE status=1 OR status=2');

mysql_close($con);

# Check user name or if user is the advisor or if user is the master professor.
if ($_SESSION['EMAIL'] != $row['financial_assistant_email'] &&
    $_SESSION['EMAIL'] != $row['transfered_email'] &&
    !isMyStudentsSubmission($row['financial_assistant_email'], $_SESSION['EMAIL']) &&
    !isMyStudentsSubmission($row['transfered_email'], $_SESSION['EMAIL']) &&
    $_SESSION['STATUS'] != 0) {
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

<label id="last_added_note_label">教师意见：</label> <br>
<textarea id="last_added_note_content" name="last_added_note" readonly rows="6" cols="60"> <?php echo $row['last_added_note']?> </textarea>

<br><br>

<input action="action" type="button" onclick="history.go(-1);" value="返回"/>

<?php
# Give student a chance to delete the request before net reporting
# is done.
if (($_SESSION['STATUS'] == 1 || $_SESSION['STATUS'] == 2) &&
    $row['request_status'] < 2) {
    echo '
        <input action="action" value="取消申请" type="button" ' .
        'onclick="if(confirm(\'确定永久取消这个申请？\'))location=\'cancel_request.php?rn=' .
        $row['request_id'] . '\';return false;" />
        ';
}
?>

</body>

</html>
