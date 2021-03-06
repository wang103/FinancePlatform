<!--If logged in, show user info instead of login form-->
<?php
session_start();

if (isset($_SESSION['USERNAME']) && !empty($_SESSION['USERNAME'])) {
    $username = $_SESSION['USERNAME'];
    $email = $_SESSION['EMAIL'];
    $first_name = $_SESSION['FIRST_NAME'];
    $last_name = $_SESSION['LAST_NAME'];
    if ($_SESSION['STATUS'] == 0) {
		$status = "财务主任/教授";
	} elseif ($_SESSION['STATUS'] == 1) {
		$status = "研究生";
    } elseif ($_SESSION['STATUS'] == 2) {
        $status = "本科生";
    } else {    // STATUS is 3.
        $status = "教授";
    }

    echo '
    <h4>账户信息</h4>
    <form name="logout_form" action="php/logout.php" method="post">
    <p id="login_p" align="left">
    <label id="login_label">用户名：</label>' . $username . '
    </p>
	<p id="login_p" align="left">
	<label id="login_label">邮箱：</label>' . $email . '
	</p>
    <p id="login_p" align="left">
    <label id="login_label">姓名：</label>' . $last_name . " " . $first_name . '
    </p>
    <p id="login_p" align="left">
    <label id="login_label">身份：</label>' . $status . '
    </p>
    <p id="login_p" align="center">
    <input type="button" onClick="location.href=\'php/show_modify_account.php\'" value="修改">
	<input type="submit" value="登出">
    </p>
    </form>';
}
else {
    echo '
    <h4>请登录账户</h4>
	<form name="login_form" action="php/login.php" method="post">
	<p id="login_p" align="left">
	<label id="login_label">用户:</label> <input id="login_input" type="text" name="username" required>
	</p>
	<p id="login_p" align="left">
	<label id="login_label">密码:</label> <input id="login_pw_input" type="password" name="pwd" required>
	</p>
	<p id="login_p" align="center">
	<input type="submit" value="登录">
	</p>
	</form>';
}
?>
