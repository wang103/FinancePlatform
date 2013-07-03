<?php
session_start();

# Check user signed in.
if (!isset($_SESSION['EMAIL']) || empty($_SESSION['EMAIL'])) {
    echo 'error code: 0';
    die();
}

# Check user name.
if (($_SESSION['LAST_NAME'] . $_SESSION['FIRST_NAME']) != $_POST['name']) {
    echo 'error code: 1';
    die();
}

# Check if there is budget.
if ($_POST['budget'] != "yes") {
    echo 'error code: 2';
    die();
}

# Check if files are complete.
if ($_POST['files'] != "yes") {
    echo 'error code: 3';
    die();
}


?>
