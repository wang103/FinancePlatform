<?php
session_start();

# Check if user is professor.
if (!isset($_SESSION['STATUS']) || $_SESSION['STATUS'] != 0) {
    echo 'error code: 0';
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

# Connect to the database.
require('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

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

if ($_POST['submit_button'] == 1) {
    // Only save.
    $request_status = 0;
    $finished_status = 2;
} else {
    // Save and finish net reporting.
    $request_status = 1;
    $finished_status = 3;
}

# Modify the row in the database.
$sql = 'UPDATE requests SET amount=' . $amount . ',' . 'have_budget=' . $have_budget . ',' . 'financial_assistant_name="' .
    $financial_assistant_name . '",' . 'page_number=' . $page_number . ',' . 'subject=' . $subject . ',' . 'subject_other="' .
    $subject_other . '",' . 'have_all_files=' . $have_all_files . ',' . 'contract_company_name="' . $contract_company_name .
    '",' . 'contract_location="' . $contract_location . '",' . 'contract_bank_number="' . $contract_bank_number . '",' .
    'contract_opener="' . $contract_opener . '",' . 'receipt_same_as_actual=' . $receipt_same_as_actual . ',' .
    'receipt_difference="' . $receipt_difference . '",' . 'professor_class="' . $professor_class . '",' . 'professor_name="' .
    $professor_name . '",' . 'expanse_number="' . $expanse_number . '",' . 'expanse_name="' . $expanse_name . '",' .
    'payment_option=' . $payment_option . ',' . 'payment_option_other="' . $payment_option_other . '",' . 'usage_optional="' .
    $usage_optional . '",' . 'note_optional="' . $note_optional. '",' . 'request_status=' . $request_status .
    ' WHERE request_id=' . $_POST['id'] . ';';

mysql_query('SET NAMES utf8');
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

mysql_close($con);

# Send a notification message to student.


# Set status to 2 if save-only, or 3 if save and net reporting,
# so the last url can display a success message.
$last_url = $_SESSION['last_url'];
header("location: " . $last_url . "?status=" . $finished_status);

die();
?>
