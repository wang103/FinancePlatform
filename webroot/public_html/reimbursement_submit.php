<?php
header('Content-type: text/html; charset=utf-8');
session_start();
?>

<html>

<head>
<title>北邮报销申请 - 提交</title>
<link rel='stylesheet' type='text/css' href='css/style00.css'>
<script src='js/utils.js'></script>
</head>

<body>

<!--Store the last visited page-->
<?php
$temp = explode("?", $_SERVER['REQUEST_URI']);
$_SESSION['last_url'] = $temp[0];
?>

<h1>北邮报销申请 - 提交</h1>

<div id='container'>

<!--Middle: Current Page's Contents-->
<div id='layout_middle' class='column'>
<?php
# Check if signed in.
if (!isset($_SESSION['EMAIL']) || empty($_SESSION['EMAIL'])) {
    echo '<FONT COLOR="Red">请先登录再提交申请</FONT>';
} elseif ($_SESSION['STATUS'] != 1 && $_SESSION['STATUS'] != 2) {
    echo '<FONT COLOR="Red">只有学生可提交申请</FONT>';
} else {
    include './php/show_submit.php';
}
?>
</div>

<!--Left: Navigation Bar-->
<div id='layout_left' class='column'>
<?php
include './show_navi.html';
?>
</div>

<!--Right: Log In-->
<div id='layout_right' class='column'>
<?php
include './php/show_login.php';
?>
</div>

</div>

</body>

</html>
