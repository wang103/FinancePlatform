<?php
session_start();

if (!isset($_SESSION['STATUS'])) {
    die();
}
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style02.css'>
</head>

<body>

<?php
session_start();

if ($_SESSION['STATUS'] == 0) {
    echo '<h3>所有报销申请</h3>';
    $qry = 'SELECT * FROM requests ORDER BY request_id DESC';
} else {
    echo '<h3>我的报销申请</h3>';
    $qry = 'SELECT * FROM requests WHERE submitter_email="' . $_SESSION['EMAIL'] . '" ORDER BY request_id DESC';
}
?>

<table border="1">
    <tr>
        <th>报销时间</th>
        <th>报销人</th>
        <th>金额（元)</th>
        <th>报销科目</th>
        <th>申请状态</th>
    </tr>
<?php
require_once('../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load all requests. Student can only see his/her requests, professor
# can see all requests.
mysql_query('SET NAMES utf8');
$result = mysql_query($qry);

require_once('utils.php');

while ($row = mysql_fetch_array($result)) {
    echo '
    <tr>
        <td><p><a href="php/show_reimbursement.php?rn=' . $row['request_id'] . '">' . $row['date_start'] . '</a></p></td>
        <td><p>' . $row['submitter_name'] . '</p></td>
        <td><p>' . $row['amount'] . '</p></td>
        <td><p>';
    getSubjectNameFromIndex($row['subject'], $row['subject_other']);
    echo '
        </p></td>
        <td><p>';
    getGeneralStatusFromIndex($row['request_status']);
    echo '
        </p></td>
    </tr>';
}

mysql_close($con);
?>
</table>

</body>

</html>
