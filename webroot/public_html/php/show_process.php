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

<h3>处理报销申请</h3>

需要处理的申请
<table border="1">
    <tr>
        <th>流水号</th>
        <th>报销时间</th>
        <th>报销人</th>
        <th>财务助理</th>
        <th>金额（元）</th>
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

# Load all unfinished requests for the user.
mysql_query('SET NAMES utf8');
if ($_SESSION['STATUS'] == 0) {
    $qry = 'SELECT * FROM requests WHERE request_status=1 OR request_status=3 ORDER BY request_id DESC';
} elseif ($_SESSION['STATUS'] == 3) {
    $qry = 'SELECT * FROM requests WHERE ';
    
    $temp = mysql_query('SELECT * FROM advisors WHERE advisor_email="' .
        $_SESSION['EMAIL'] . '"');
    $counter = 1;
    while ($temp_row = mysql_fetch_array($temp)) {
        if ($counter == 1) {
            $qry = $qry . '(';
        } else {
            $qry = $qry . 'OR ';
        }

        $qry = $qry . 'financial_assistant_email="' . $temp_row['student_email'] . '" ';
        
        $counter = $counter + 1;
    }

    if ($counter > 1) {
        $qry = $qry . ') AND request_status=0 ORDER BY request_id DESC';
    } else {
        // Make return result empty on purpose.
        $qry = 'SELECT * FROM requests WHERE request_status=666';
    }
} else {
    $qry = 'SELECT * FROM requests WHERE request_status=2 AND ((financial_assistant_email="' .
       $_SESSION['EMAIL'] . '" AND transfered_email IS NULL) OR transfered_email="' .
       $_SESSION['EMAIL'] . '") ORDER BY request_id DESC';
}
$result = mysql_query($qry);

require_once('utils.php');

while ($row = mysql_fetch_array($result)) {
    if ($row['request_status'] == 0) {
        $dest_page = 'php/step_advisor_agree.php';
    } elseif ($row['request_status'] == 1) {
        $dest_page = 'php/step_finish_net_report.php';
    } elseif ($row['request_status'] == 2) {
        $dest_page = 'php/step_student_finish.php';
    } else {    // status is 3
        $dest_page = 'php/step_professor_finish.php';
    }
    $dest_page .= '?rn=' . $row['request_id'];

    echo '
    <tr>
        <td><p>' . $row['request_id'] . '</p></td>
        <td><p>' . $row['date_start'] . '</p></td>
        <td><p>' . $row['submitter_name'] . '</p></td>
        <td><p>' . $row['financial_assistant_name'];

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
        <td><p><a href="' . $dest_page . '">';
    getStatusFromIndex($row['request_status']);
    echo '
        </a></p></td>
    </tr>';
}

mysql_close($con);
?>
</table>

<?php
if (isset($_SESSION['feedback'])) {
    if ($_SESSION['feedback'] == 2) {
        echo '
        <script>
        alert("报销申请内容保存成功！");
        </script>';
    } elseif ($_SESSION['feedback'] == 3) {
        echo '
        <script>
        alert("报销申请网报成功！");
        </script>';
    } elseif ($_SESSION['feedback'] == 4) {
        echo '
        <script>
        alert("报销完成。请等待老师添加意见。");
        </script>';
    } elseif ($_SESSION['feedback'] == 5) {
        echo '
        <script>
        alert("报销完成!");
        </script>';
    } elseif ($_SESSION['feedback'] == 6) {
        echo '
        <script>
        alert("申请通过。请等待主任老师完成网报。");
        </script>';
    } elseif ($_SESSION['feedback'] == 8) {
        echo '
        <script>
        alert("申请不通过。此申请永久终止。");
        </script>';
    }

    unset($_SESSION['feedback']);
}
?>

</body>

</html>
