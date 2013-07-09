<?php
session_start();

// Check if signed in.
if (!isset($_SESSION['EMAIL'])) {
    die();
}

# Connect to the database.
require_once('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load user details.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM advisors WHERE student_email="' .
    $_SESSION['EMAIL'] . '"');
$row = mysql_fetch_array($result);

$cur_advisor_email = $row['advisor_email'];
$cur_advisor = false;

if (isset($cur_advisor_email) && strlen($cur_advisor_email) > 0) {
    $result = mysql_query('SELECT * FROM users WHERE email="' . $cur_advisor_email . '"');
    $cur_advisor = mysql_fetch_array($result);
}

$advisors = mysql_query('SELECT * FROM users WHERE status=3 ORDER BY last_name');

mysql_close($con);
?>

<html>

<head>
<title>修改个人信息</title>
<link rel='stylesheet' type='text/css' href='../css/style03.css'>
<script src='../js/validate_form.js'></script>
</head>

<body>

<h3>修改个人信息</h3>

<form name="modify_account_form" action="modify_account.php" onsubmit="return validateModifyAccountForm()" method="post">

<p>
<label id="email">邮件地址：</label>
<input type="email" name="email" required value="<?php echo $_SESSION['EMAIL']?>">
</p>

<p>
<label id="last_name">姓：</label>
<input type="text" name="last_name" required value="<?php echo $_SESSION['LAST_NAME']?>">

<label id="first_name">名：</label>
<input type="text" name="first_name" required value="<?php echo $_SESSION['FIRST_NAME']?>">
</p>

<?php
if ($_SESSION['STATUS'] != 0 && $_SESSION['STATUS'] != 3) {
    echo '
    <p>
    <label id="id_number">学号：</label>
    <input type="text" name="id_number" required value="' .
    $_SESSION['ID_NUMBER'] . '">
    </p>';
}
?>

<p>
<label id="new_pw">新密码：</label>
<input type="password" name="new_pw" value="">

<label id="new_pw_again">重新输入新密码：</label>
<input type="password" name="new_pw_again" value="">
</p>

<?php
if ($_SESSION['STATUS'] != 0 && $_SESSION['STATUS'] != 3) {
    echo '
    <p>
    <label id="cur_advisor">当前负责老师：';

    if ($cur_advisor == false) {
        echo '无';
    } else {
        echo $cur_advisor['last_name'] . $cur_advisor['first_name'];
    }
    echo '</label>

    <label id="new_advisor">改变负责老师：</label>
    <select name="advisor" required>';

    while ($advisor_row = mysql_fetch_array($advisors)) {
        echo '<option ';
        if($cur_advisor_email==$advisor_row['email']) {
            echo 'selected';
        }
        echo ' value="' . $advisor_row['email'] . '">' . $advisor_row['last_name'] .
            $advisor_row['first_name'] . '</option>';
    }

    echo '</select></p>';
}
?>

<hr>

<p>
<label id="old_pw">密码：</label>
<input type="password" name="old_pw" required value="">
</p>

<input action="action" type="button" onclick="history.go(-1);" value="返回"/>
<button type="submit" name="submit_button">提交</button>

</form>

</body>

</html>
