<html>

<head>
<title>北邮报销申请 - 查看</title>
<link rel='stylesheet' type='text/css' href='css/style00.css'>
<script src='js/utils.js'></script>
</head>

<body>

<!--Store the last visited page-->
<?php
session_start();
$temp = explode("?", $_SERVER['REQUEST_URI']);
$_SESSION['last_url'] = $temp[0];
?>

<h1>北邮报销申请 - 查看</h1>

<div id='container'>

<!--Middle: Current Page's Contents-->
<div id='layout_middle' class='column'>
<?php
# Check if signed in.
if (!isset($_SESSION['EMAIL']) || empty($_SESSION['EMAIL'])) {
    echo '<FONT COLOR="Red">请先登录再查看申请</FONT>';
} else {
    include 'php/show_check.php';
}
?>
</div>

<!--Left: Navigation Bar-->
<div id='layout_left' class='column'>
<?php
include 'show_navi.html';
?>
</div>

<!--Right: Log In-->
<div id='layout_right' class='column'>
<?php
include 'php/show_login.php';
?>
</div>

</div>

</body>

</html>
