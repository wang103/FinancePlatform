<?php
session_start();

# Unset the varibales stored in the session.
unset($_SESSION['EMAIL']);
unset($_SESSION['FIRST_NAME']);
unset($_SESSION['LAST_NAME']);
unset($_SESSION['ID_NUMBER']);
unset($_SESSION['STATUS']);

# Go back.
$last_url = $_SESSION['last_url'];
header("location: " . $last_url);
die();
?>
