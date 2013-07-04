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

# Load all unfinished requests for the user.
mysql_query('SET NAMES utf8');
if ($_SESSION['STATUS'] == 0) {
    $qry = 'SELECT * FROM requests WHERE request_status=0 OR request_status=2 OR (request_status=1 AND submitter_email="' . $_SESSION['EMAIL'] . '") ORDER BY request_id DESC';
} else {
    $qry = 'SELECT * FROM requests WHERE submitter_email="' . $_SESSION['EMAIL'] . '" AND request_status=1 ORDER BY request_id DESC';
}
$result = mysql_query($qry);

require('utils.php');

while ($row = mysql_fetch_array($result)) {
    if ($row['request_status'] == 0) {
        $dest_page = 'php/step_finish_net_report.php';
    } elseif ($row['request_status'] == 1) {
        $dest_page = 'php/step_student_finish.php';
    } else {    // status is 2
        $dest_page = 'php/step_professor_finish.php';
    }
    $dest_page = $dest_page . '?rn=' . $row['request_id'];

    echo '
    <tr>
        <td><p>' . $row['date'] . '</p></td>
        <td><p>' . $row['submitter_name'] . '</p></td>
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
if (isset($_GET['status'])) {
    if ($_GET['status'] == 2) {
        echo '
        <script>
        alert("报销申请内容保存成功！");
        </script>';
    } elseif ($_GET['status'] == 3) {
        echo '
        <script>
        alert("报销申请网报成功！");
        </script>';
    } elseif ($_GET['status'] == 4) {
        echo '
        <script>
        alert("报销完成。请等待老师添加意见。");
        </script>';
    } elseif ($_GET['status'] == 5) {
        echo '
        <script>
        alert("报销完成!");
        </script>';
    }
}
?>

</body>

</html>
