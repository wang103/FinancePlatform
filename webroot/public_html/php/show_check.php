<?php
session_start();

if (!isset($_SESSION['STATUS'])) {
    die();
}
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style02.css'>
<script src='../js/sorttable.js'></script>
</head>

<body>

<?php
# Connect to the database.
require_once(dirname(__FILE__) . '/../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

mysql_query('SET NAMES utf8');

if ($_SESSION['STATUS'] == 0) {
    echo '<h3>所有报销申请</h3>';
    $qry = 'SELECT * FROM requests WHERE request_status!=7 ORDER BY request_id DESC';
} elseif ($_SESSION['STATUS'] == 3) {
    echo '<h3>我的学生的报销申请</h3>';

    $qry = 'SELECT * FROM requests ';
    
    $temp = mysql_query('SELECT * FROM advisors WHERE advisor_username="' .
        $_SESSION['USERNAME'] . '"');
    $counter = 1;
    while ($temp_row = mysql_fetch_array($temp)) {
        if ($counter == 1) {
            $qry = $qry . 'WHERE (';
        } else {
            $qry = $qry . 'OR ';
        }

        $qry = $qry . 'financial_assistant_username="' . $temp_row['student_username'] .
            '" OR transfered_username="' . $temp_row['student_username'] . '" ';

        $counter = $counter + 1;
    }

    if ($counter > 1) {
        $qry = $qry . ') AND request_status!=7 ORDER BY request_id DESC';
    } else {
        // Make return result empty on purpose.
        $qry = 'SELECT * FROM requests WHERE request_status=666'; 
    }
} else {
    echo '<h3>我的报销申请</h3>';
    $qry = 'SELECT * FROM requests WHERE (financial_assistant_username="' .
        $_SESSION['USERNAME'] . '" OR transfered_username="' . $_SESSION['USERNAME'] .
        '") AND request_status!=7 ORDER BY request_id DESC';
}
?>

<table class="sortable" id="check_table" border="1">
    <tr>
        <th>流水号</th>
        <th>报销时间</th>
        <th>报销人</th>
        <th>财务助理</th>
        <th>负责老师</th>
        <th>金额（元)</th>
        <th>报销科目</th>
        <th>申请状态</th>
    </tr>

<?php
# Load all requests. Student and student's professor can only see
# the student's requests, master professor can see all requests.
$result = mysql_query($qry);

require_once(dirname(__FILE__) . '/utils.php');

while ($row = mysql_fetch_array($result)) {
    echo '
    <tr>
        <td><p>' . $row['request_id'] . '</p></td>
        <td><p><a href="php/show_reimbursement.php?rn=' . $row['request_id'] . '">' . $row['date_start'] . '</a></p></td>
        <td><p>' . $row['submitter_name'] . '</p></td>
        <td><p>' . $row['financial_assistant_name'];
    
    if (isset($row['transfered_name'])) {
        echo '=>' . $row['transfered_name'];
    }

    echo '</p></td>
        <td><p>' . $row['professor_name'] . '</p></td>
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

<?php
if (isset($_SESSION['feedback'])) {
    if ($_SESSION['feedback'] == 9) {
        echo '
        <script>
        alert("报销申请已被永久取消！");
        </script>';
    }

    unset($_SESSION['feedback']);
}
?>

</body>

</html>
