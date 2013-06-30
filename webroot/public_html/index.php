<html>

<head>
<title>北邮报销申请 - 主页</title>
<link rel='stylesheet' type='text/css' href='css/style00.css'>
</head>

<body>

<!--Store the last visited page-->
<?php
session_start();
$_SESSION['last_url'] = $_SERVER['REQUEST_URI'];
?>

<h1>北邮报销申请 - 主页</h1>

<!--Left: Navigation Bar-->
<div id='layout_left'>
<?php
include 'show_navi.html';
?>
</div>

<!--Middle: Current Page's Contents-->
<div id='layout_middle'>
<?php
include 'php/show_announcement.php';
?>
</div>

<!--Right: Log In-->
<div id='layout_right'>
<?php
include 'php/show_login.php';
?>
</div>

</body>

</html>
