<?php
session_start();

require('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Escape special characters.
if (!get_magic_quotes_gpc()) {
    $email = mysql_real_escape_string($_POST['email']);
} else {
    $email = $_POST['email'];
}
$pwd = $_POST['pwd'];

#Check against the database.
$qry = "SELECT first_name,last_name,status FROM users WHERE email='" . $email . "' AND password='" . md5($pwd) . "'"; 
$login_result = mysql_query($qry);

mysql_close($con);

# Store the authentication result in session.
if (mysql_num_rows($login_result) > 0) {
    # Regenerate session ID to prevent session fixation attacks.
    session_regenerate_id();

    # Login successful.
    $member = mysql_fetch_assoc($login_result);

    $_SESSION['EMAIL'] = $email;
    $_SESSION['FIRST_NAME'] = $member['first_name'];
    $_SESSION['LAST_NAME'] = $member['last_name'];
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
