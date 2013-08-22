<?php
session_start();

# Check user signed in.
if (!isset($_SESSION['USERNAME']) || empty($_SESSION['USERNAME'])) {
    echo 'error code: 0';
    die();
}

# Connect to the database.
require_once(dirname(__FILE__) . '/../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Update the account info from the table.
# First check user's password.
mysql_query('SET NAMES utf8');
$qry = 'SELECT * FROM users WHERE username="' . $_SESSION['USERNAME'] .
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
$advisor_username = $_POST['advisor'];

# Update user information.
if ($_SESSION['STATUS'] != 0 && $_SESSION['STATUS'] != 3) {
    # Student.
    $qry = 'UPDATE users SET email="' . $email . '", password="' . $pw . '", first_name="' .
        $first_name . '", last_name="' . $last_name . '", id_number="' . $id_number .
        '" WHERE username="' . $_SESSION['USERNAME'] . '"';
} else {
    # Professor.
    $qry = 'UPDATE users SET email="' . $email . '", password="' . $pw . '", first_name="' .
        $first_name . '", last_name="' . $last_name .
        '" WHERE username="' . $_SESSION['USERNAME'] . '"';
}

if (!mysql_query($qry, $con)) {
    die('Error: ' . mysql_error());
}

# Update advisor.
if ($_SESSION['STATUS'] != 0 && $_SESSION['STATUS'] != 3) {
    # Student.
    $qry = 'UPDATE advisors SET advisor_username="' .
        $advisor_username . '" WHERE student_username="' .
        $_SESSION['USERNAME'] . '"';

    if (!mysql_query($qry, $con)) {
        die('Error: ' . mysql_error());
    }
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
