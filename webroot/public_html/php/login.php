<?php
session_start();

require_once(dirname(__FILE__) . '/../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Escape special characters.
if (!get_magic_quotes_gpc()) {
    $username = mysql_real_escape_string($_POST['username']);
} else {
    $username = $_POST['username'];
}
$pwd = $_POST['pwd'];

#Check against the database.
$qry = "SELECT * FROM users WHERE username='" . $username . "' AND password='" . md5($pwd) . "'"; 
mysql_query('SET NAMES utf8');
$login_result = mysql_query($qry);

mysql_close($con);

# Store the authentication result in session.
if (mysql_num_rows($login_result) > 0) {
    # Regenerate session ID to prevent session fixation attacks.
    session_regenerate_id();

    # Login successful.
    $member = mysql_fetch_assoc($login_result);

    $_SESSION['USERNAME'] = $username;
    $_SESSION['EMAIL'] = $member['email'];
    $_SESSION['FIRST_NAME'] = $member['first_name'];
    $_SESSION['LAST_NAME'] = $member['last_name'];
    $_SESSION['ID_NUMBER'] = $member['id_number'];
    $_SESSION['STATUS'] = $member['status'];

    # Write session to disc.
    session_write_close();

    # Go back.
    $last_url = $_SESSION['last_url'];
    header("location: " . $last_url);
    die();
} else {
    # Login failed.
    header("location: login_failed.php");
    die();
}
?>
