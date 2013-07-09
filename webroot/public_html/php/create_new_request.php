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

# Check submitter is a student.
if ($_SESSION['STATUS'] != 1 && $_SESSION['STATUS'] != 2) {
    echo 'error code: 4';
    die();
}

require_once('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

$submitter_email = $_SESSION['EMAIL'];
$submitter_name = $_POST['name'];
$submitter_id_number = $_POST['id_number'];
$submit_date = $_POST['date'];
$amount = $_POST['amount'];
$have_budget = 1;
$financial_assistant_name = $_POST['finance_assist_name'];
$page_number = $_POST['pages'];
$subject_other = "";
if ($_POST['class'] == "equipment") {
    $subject = 0;
} elseif ($_POST['class'] == "material") {
    $subject = 1;
} elseif ($_POST['class'] == "meeting") {
    $subject = 2;
} elseif ($_POST['class'] == "layout") {
    $subject = 3;
} elseif ($_POST['class'] == "software") {
    $subject = 4;
} elseif ($_POST['class'] == "travel") {
    $subject = 5;
} else {    // others
    $subject = 6;
    $subject_other = $_POST['other_subject'];
}
$have_all_files = 1;
$contract_company_name = $_POST['company_name'];
$contract_location = $_POST['company_location'];
$contract_bank_number = $_POST['bank_card'];
$contract_opener = $_POST['opener'];
if ($_POST['receipt_agree'] == "yes") {
    $receipt_same_as_actual = 1;
    $receipt_difference = "";
} else {
    $receipt_same_as_actual = 0;
    $receipt_difference = $_POST['receipt_content'];
}
$professor_class = $_POST['professor_class'];
$professor_name = $_POST['professor_name'];
$expanse_number = $_POST['card_number'];
$expanse_name = $_POST['card_name'];
$payment_option_other = "";
if ($_POST['payment_option'] == "post") {
    $payment_option = 0;
} elseif ($_POST['payment_option'] == "tele") {
    $payment_option = 1;
} elseif ($_POST['payment_option'] == "cash") {
    $payment_option = 2;
} elseif ($_POST['payment_option'] == "check") {
    $payment_option = 3;
} else {    // others
    $payment_option = 4;
    $payment_option_other = $_POST['other_payment_option'];
}
$usage_optional = $_POST['usage'];
$note_optional = $_POST['note'];
$request_status = 0;

# Insert new request into the database.
$sql = 'INSERT INTO requests (submitter_email, submitter_name, submitter_id_number, date_start, amount,' .
    'have_budget, financial_assistant_name, page_number, subject, subject_other,' .
    'have_all_files, contract_company_name, contract_location, contract_bank_number,' .
    'contract_opener, receipt_same_as_actual, receipt_difference, professor_class,' .
    'professor_name, expanse_number, expanse_name, payment_option, payment_option_other,' .
    'usage_optional, note_optional, request_status) VALUES (' .
    '"' . $submitter_email . '", "' . $submitter_name . '", "' . $submitter_id_number . '", "' . $submit_date . '", ' . $amount .
    ', ' . $have_budget . ', "' . $financial_assistant_name . '", ' . $page_number . ', ' . $subject . ', "'. $subject_other .
    '", ' . $have_all_files . ', "'. $contract_company_name . '", "' . $contract_location . '", "' . $contract_bank_number .
    '", "' . $contract_opener . '", ' . $receipt_same_as_actual . ', "' . $receipt_difference . '", "' . $professor_class .
    '", "' . $professor_name . '", "' . $expanse_number . '", "' . $expanse_name . '", ' . $payment_option . ', "' . $payment_option_other .
    '", "' . $usage_optional . '", "' . $note_optional . '", ' . $request_status . ')';

mysql_query('SET NAMES utf8');
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

mysql_close($con);

# Send a notification message to professor.


# Set session var to 1 so the last url can display a success message.
$last_url = $_SESSION['last_url'];
$_SESSION['feedback'] = 1;
header("location: " . $last_url);

die();
?>
