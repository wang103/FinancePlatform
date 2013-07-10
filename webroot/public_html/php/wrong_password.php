<?php
header('Content-type: text/html; charset=utf-8');
session_start();
?>

<html>

<head>
<title>密码错误</title>
</head>

<body>
<h1>密码错误！</h1>

<?php
echo '
<p>
请检查你输入的密码。点<a href="' . $_SESSION['last_url'] . '">此</a>回到上一页面。
</p>
';
?>

</body>

</html>
