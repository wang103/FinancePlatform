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

# Connect to the database.
require_once('../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

mysql_query('SET NAMES utf8');

if ($_SESSION['STATUS'] == 0) {
    echo '<h3>所有报销申请</h3>';
    $qry = 'SELECT * FROM requests ORDER BY request_id DESC';
} elseif ($_SESSION['STATUS'] == 3) {
    echo '<h3>我的学生的报销申请</h3>';

    $qry = 'SELECT * FROM requests WHERE ';
    
    $temp = mysql_query('SELECT * FROM advisors WHERE advisor_email="' .
        $_SESSION['EMAIL'] . '"');
    $counter = 1;
    while ($temp_row = mysql_fetch_array($temp)) {
        if ($counter != 1) {
            $qry = $qry . 'OR ';
        }

        $qry = $qry . 'submitter_email="' . $temp_row['student_email'] .
            '" OR transfered_email="' . $temp_row['student_email'] . '" ';

        $counter = $counter + 1;
    }
    
    $qry = $qry . 'ORDER BY request_id DESC';
} else {
    echo '<h3>我的报销申请</h3>';
    $qry = 'SELECT * FROM requests WHERE submitter_email="' .
        $_SESSION['EMAIL'] . '" OR transfered_email="' . $_SESSION['EMAIL'] .
        '" ORDER BY request_id DESC';
}
?>

<table border="1">
    <tr>
        <th>流水号</th>
        <th>报销时间</th>
        <th>报销人</th>
        <th>金额（元)</th>
        <th>报销科目</th>
        <th>申请状态</th>
    </tr>

<?php
# Load all requests. Student and student's professor can only see
# the student's requests, master professor can see all requests.
$result = mysql_query($qry);

require_once('utils.php');

while ($row = mysql_fetch_array($result)) {
    echo '
    <tr>
        <td><p>' . $row['request_id'] . '</p></td>
        <td><p><a href="php/show_reimbursement.php?rn=' . $row['request_id'] . '">' . $row['date_start'] . '</a></p></td>
        <td><p>' . $row['submitter_name'];
    
    if (isset($row['transfered_email'])) {
        $sql_usr = mysql_query('SELECT * FROM users WHERE email="' . $row['transfered_email'] . '"');
        $usr = mysql_fetch_array($sql_usr);
        echo '=>' . $usr['last_name'] . $usr['first_name'];
    }
    
    echo '</p></td>
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
