<?php
session_start();

if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 0) {
    die();
}
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style02.css'>
</head>

<body>

<h3>处理报销申请</h3>

需要处理的申请
<table border="1">
    <tr>
        <th>报销时间</th>
        <th>报销人</th>
        <th>金额（元）</th>
        <th>报销科目</th>
        <th>申请状态</th>
    </tr>
<?php
require('../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load unfinished requests.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM requests WHERE request_status!=2');

require('utils.php');

while ($row = mysql_fetch_array($result)) {
    echo '
    <tr>
        <td><p>' . $row['date'] . '</p></td>
        <td><p>' . $row['submitter_name'] . '</p></td>
        <td><p>' . $row['amount'] . '</p></td>
        <td><p>';
    getSubjectNameFromIndex($row['subject'], $row['subject_other']);
    echo '
        </p></td>
        <td><p>';
    getStatusFromIndex($row['request_status']);
    echo '
        </p></td>
    </tr>';
}

mysql_close($con);
?>
</table>

</body>

</html>
