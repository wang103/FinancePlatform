<?php
session_start();

# Check user signed in.
if (!isset($_SESSION['EMAIL']) || empty($_SESSION['EMAIL'])) {
    echo 'error code: 0';
    die();
}

# Connect to the database.
require_once('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Update the account info from the table.
# First check user's password.
mysql_query('SET NAMES utf8');
$qry = 'SELECT * FROM users WHERE email="' . $_SESSION['EMAIL'] .
    '" AND password="' . md5($_POST['old_pw']) . '"';
$result = mysql_query($qry, $con);

if (mysql_num_rows($result) == 0) {
    # Incorrect password.
    mysql_close($con);
    header("location: wrong_password.php");
    die();
}

$email = $_POST['email'];
$last_name = $_POST['last_name'];
$first_name = $_POST['first_name'];
$id_number = $_POST['id_number'];
$pw = $_POST['new_pw'];
if (!isset($pw) || trim($pw) === '') {
    $pw = $_POST['old_pw'];
}
$pw = md5($pw);
$advisor_email = $_POST['advisor'];

# Update user information.
$qry = 'UPDATE users SET email="' . $email . '", password="' . $pw . '", first_name="' .
    $first_name . '", last_name="' . $last_name . '", id_number="' . $id_number .
    '" WHERE email="' . $_SESSION['EMAIL'] . '"';

if (!mysql_query($qry, $con)) {
    die('Error: ' . mysql_error());
}

# Update advisor.
$qry = 'UPDATE advisors SET advisor_email="' . $advisor_email .
    '" WHERE student_email="' . $email . '"';

if (!mysql_query($qry, $con)) {
    die('Error: ' . mysql_error());
}

# Update rows in requests.
$qry = 'UPDATE requests SET submitter_name="' . $last_name . $first_name .
    '", submitter_id_number="' . $id_number . '" WHERE submitter_email="' .
    $email . '"';

if (!mysql_query($qry, $con)) {
    die('Error: ' . mysql_error());
}
mysql_close($con);

$_SESSION['EMAIL'] = $email;
$_SESSION['FIRST_NAME'] = $first_name;
$_SESSION['LAST_NAME'] = $last_name;
$_SESSION['ID_NUMBER'] = $id_number;

# Set session var to 7 so the last url can display a success message.
$_SESSION['feedback'] = 7;

# Write session to disc.
session_write_close();

header('location: ../index.php');
die();
?>
