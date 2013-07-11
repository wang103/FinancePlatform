<?php
session_start();

# Check if user is the master professor.
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
require_once('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

$name = $_POST['name'];
$id_number = $_POST['id_number'];
$transfered_email = 'NULL';
if ($_POST['transfer_sel'] == "yes") {
    $transfered_email = '"' . $_POST['transfer'] . '"';
}
$net_report_date = date('Y-m-d');
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
if ($_POST['special'] == "yes") {
    $is_special = 1;
} else {
    $is_special = 0;
}
$intel_platform_id = 'NULL';
$asset_platform_id = 'NULL';
if ($is_special == 1) {
    if (isset($_POST['special_int_intel']) && strlen(trim($_POST['special_int_intel'])) > 0) {
        $intel_platform_id = $_POST['special_int_intel'];
    }
    if (isset($_POST['special_int_asset']) && strlen(trim($_POST['special_int_asset'])) > 0) {
        $asset_platform_id = $_POST['special_int_asset'];
    }
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

if ($_POST['submit_button'] == 0) {
    // Didn't agree.
    $request_status = 6;
    $finished_status = 8;
} elseif ($_POST['submit_button'] == 1) {
    // Only save.
    $request_status = 1;
    $finished_status = 2;
} else {
    // Save and finish net reporting.
    $request_status = 2;
    $finished_status = 3;
}

# Modify the row in the database.
$sql = 'UPDATE requests SET submitter_name="' . $name . '", submitter_id_number="' .
    $id_number . '", transfered_email=' . $transfered_email .
    ', date_net_report_finished="' . $net_report_date . '", amount=' . $amount .
    ',' . 'have_budget=' . $have_budget . ',' . 'financial_assistant_name="' .
    $financial_assistant_name . '",' . 'page_number=' . $page_number . ',' .
    'subject=' . $subject . ',' . 'subject_other="' . $subject_other . '",' .
    'is_special=' . $is_special . ',' . 'intel_platform_id=' . $intel_platform_id . ',' .
    'asset_platform_id=' . $asset_platform_id . ',' .
    'have_all_files=' . $have_all_files . ',' . 'contract_company_name="' .
    $contract_company_name . '",' . 'contract_location="' . $contract_location .
    '",' . 'contract_bank_number="' . $contract_bank_number . '",' .
    'contract_opener="' . $contract_opener . '",' . 'receipt_same_as_actual=' .
    $receipt_same_as_actual . ',' . 'receipt_difference="' .
    $receipt_difference . '",' . 'professor_class="' . $professor_class . '",' .
    'professor_name="' . $professor_name . '",' . 'expanse_number="' .
    $expanse_number . '",' . 'expanse_name="' . $expanse_name . '",' .
    'payment_option=' . $payment_option . ',' . 'payment_option_other="' .
    $payment_option_other . '",' . 'usage_optional="' . $usage_optional . '",' .
    'note_optional="' . $note_optional. '",' . 'request_status=' .
    $request_status . ' WHERE request_id=' . $_POST['id'] . ';';

mysql_query('SET NAMES utf8');
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
}

# Send a notification message to student.
if (SEND_EMAIL && $request_status != 1) {
    $sql = 'SELECT * FROM requests WHERE request_id=' . $_POST['id'];
    $result = mysql_query($sql, $con);
    $student = mysql_fetch_assoc($result);

    if ($request_status == 6) {
        notifyWithEmail($student['financial_assistant_email'], 8);
    } else {
        if (isset($student['transfered_email'])) {
            notifyWithEmail($student['transfered_email'], 5);
            notifyWithEmail($student['financial_assistant_email'], 6);
        } else {
            notifyWithEmail($student['financial_assistant_email'], 2);
        }
    }
}

mysql_close($con);

# Set feedback to 8 if didn't agree, to 2 if save-only,
# or to 3 if save and net reporting,
# so the last url can display a success message.
$last_url = $_SESSION['last_url'];
$_SESSION['feedback'] = $finished_status;
header("location: " . $last_url);

die();
?>
