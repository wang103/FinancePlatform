<!--If logged in, show user info instead of login form-->
<?php
session_start();

if (isset($_SESSION['EMAIL']) && !empty($_SESSION['EMAIL'])) {
    if ($_SESSION['STATUS'] == 0) {
		$status = "教授";
	} elseif ($_SESSION['STATUS'] == 1) {
		$status = "研究生";
    } else {
        $status = "本科生";
    }

	echo '
	<form name="logout_form" action="php/logout.php" method="post">
	<p id="login_p">
	<label id="login_label">User:</label> ' . $_SESSION['USER_NAME'] . '
	</p>
	<p id="login_p">' . $identity .
	'</p>
	<p id="login_p" align="center">
	<input type="submit" value="Logout">
	</p>';
}
else {
    echo '
    <h4>请登录账户</h4>
	<form name="login_form" action="php/login.php" method="post">
	<p id="login_p" align="left">
	<label id="login_label">邮箱地址（email）:</label> <input id="login_input" type="email" name="email" required>
	</p>
	<p id="login_p" align="left">
	<label id="login_label">密码（password）:</label> <input id="login_input" type="password" name="pwd" required>
	</p>
	<p id="login_p" align="center">
	<input type="submit" value="登录">
	</p>
	</form>';
}
?>
