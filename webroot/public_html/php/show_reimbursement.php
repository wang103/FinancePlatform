<?php
session_start();

# Check user signed in.
if (!isset($_SESSION['EMAIL']) || empty($_SESSION['EMAIL'])) {
    echo 'error code: 0';
    die();
}

# Connect to the database.
require('../../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load request details.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM requests WHERE request_id=' . $_GET['rn']);
$row = mysql_fetch_array($result);

mysql_close($con);

# Check user name or if user is a professor.
if ($_SESSION['EMAIL'] != $row['submitter_email'] && $_SESSION['STATUS'] != 0) {
    echo 'error code: 1';
    die();
}
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style01.css'>
<script src='../js/interface_listener.js'></script>
</head>

<body>

<?php
require('utils.php');
?>

<p>
<label id="request_status">申请目前状态：</label> <?php getGeneralStatusFromIndex($row['request_status']) ?>

<label id="id">申请流水号：</label> <input type="text" name="id" required readonly value="<?php echo $row['request_id']?>">
</p>

<label id="name">姓名：</label> <input type="text" name="name" required readonly value="<?php echo $row['submitter_name']?>">

<label id="id_number">学号：</label> <input type="text" name="id_number" required readonly value="<?php echo $row['submitter_id_number']?>">

<label id="date">日期：</label> <input type="date" name="date" required readonly value="<?php echo $row['date']?>">

<br>

<p>报销金额：<input type="number" name="amount" min=0.0 step=0.01 required readonly value="<?php echo $row['amount']?>">元</p>

<p>
是否有预算（有预算才可提交报销申请）？
<input type="radio" name="budget" onclick="budgetChanged(this);" disabled='disabled' value="yes" checked>有
<input type="radio" name="budget" onclick="budgetChanged(this);" disabled='disabled' value="no">没有
</p>

<p>
财务助理姓名：<input type="text" name="finance_assist_name" required readonly value="<?php echo $row['financial_assistant_name']?>">
</p>

<p>
单据数量：<input type="number" name="pages" min=0 step=1 required readonly value="<?php echo $row['page_number']?>">页
</p>

<p>
报销科目：
<select name="class" required disabled="disabled" onchange="subjectChanged(this)">
    <option <?php if($row['subject']==0) echo 'selected'?> value="equipment">设备</option>
    <option <?php if($row['subject']==1) echo 'selected'?> value="material">材料</option>
    <option <?php if($row['subject']==2) echo 'selected'?> value="meeting">会议</option>
    <option <?php if($row['subject']==3) echo 'selected'?> value="layout">版面</option>
    <option <?php if($row['subject']==4) echo 'selected'?> value="software">软件</option>
    <option <?php if($row['subject']==5) echo 'selected'?> value="travel">差旅</option>
    <option <?php if($row['subject']==6) echo 'selected'?> value="others">其他</option>
</select>

<br>

<label id="other_subject_label" <?php if($row['subject']!=6) echo 'style="display: none"'?>>请填写具体报销科目：</label>
<input id="other_subject_input" <?php if($row['subject']!=6) echo 'style="display: none"'?> type="text" name="other_subject" readonly value="<?php echo $row['subject_other']?>">
</p>

报销材料是否齐全（齐全才可提交报销申请）？
<input type="radio" name="files" onclick="filesChanged(this);" disabled='disabled' value="yes" checked>是
<input type="radio" name="files" onclick="filesChanged(this);" disabled='disabled' value="no">否

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>合同内容</legend>
    <label id="company_name">公司名称：</label> <input type="text" name="company_name" readonly value="<?php echo $row['contract_company_name']?>">
    
    <label id="company_location">地区：</label>
    <select name="company_location" disabled="disabled">
        <option <?php if($row['contract_location']==0) echo 'selected'?> value="beijing">北京</option>
        <option <?php if($row['contract_location']==1) echo 'selected'?> value="remote">外地</option>
        <option <?php if($row['contract_location']==2) echo 'selected'?> value="others">其他</option>
    </select>
    
    <br>

    <label id="bank_card">银行卡号：</label> <input type="text" name="bank_card" readonly value="<?php echo $row['contract_bank_number']?>">

    <label id="opener">开户行：</label> <input type="text" name="opener" readonly value="<?php echo $row['contract_opener']?>">
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>发票内容</legend>

    <label>是否和实际内容一致？</label>
    <input type="radio" name="receipt_agree" onclick="receiptChanged(this);" disabled='disabled' value="yes" <?php if($row['receipt_same_as_actual']==1) echo "checked"?>>是
    <input type="radio" name="receipt_agree" onclick="receiptChanged(this);" disabled='disabled' value="no" <?php if($row['receipt_same_as_actual']==0) echo "checked"?>>不是

    <br>

    <label id="receipt_label" <?php if($row['receipt_same_as_actual']==1) echo 'style="display: none"'?>>请填写不一致的内容：</label>
    <textarea id="receipt_content" name="receipt_content" <?php if($row['receipt_same_as_actual']==1) echo 'style="display: none"'?> rows="6" cols="60" readonly> <?php echo $row['receipt_difference']?> </textarea>
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>负责老师</legend>
    
    <label>导师组组别：</label>
    <input type="text" name="professor_class" required readonly value="<?php echo $row['professor_class']?>">

    <label id="professor_name_label">导师姓名：</label>
    <input type="text" name="professor_name" required readonly value="<?php echo $row['professor_name']?>">
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>支出项目</legend>
    
    <label>经费卡编号：</label>
    <input type="text" name="card_number" required readonly value="<?php echo $row['expanse_number']?>">

    <label id="card_name_label">经费卡名称：</label>
    <input type="text" name="card_name" required readonly value="<?php echo $row['expanse_name']?>">
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>支付方式</legend>
    
    <label>支付方式：</label>
    <select name="payment_option" required disabled="disabled" onchange="paymentChanged(this)">
        <option <?php if($row['payment_option']==0) echo 'selected'?> value="post">邮局代发</option>
        <option <?php if($row['payment_option']==1) echo 'selected'?> value="tele">汇款</option>
        <option <?php if($row['payment_option']==2) echo 'selected'?> value="cash">现金</option>
        <option <?php if($row['payment_option']==3) echo 'selected'?> value="check">支票</option>
        <option <?php if($row['payment_option']==4) echo 'selected'?> value="others">其他</option>
    </select>

    <label id="other_payment_label" <?php if($row['payment_option']!=4) echo 'style="display: none"'?>>请填写支付方式：</label>
    <input id="other_payment_input" <?php if($row['payment_option']!=4) echo 'style="display: none"'?> type="text" name="other_payment_option" readonly value="<?php echo $row['payment_option_other']?>">
</fieldset>

<br><br>

<label id="usage_label">用途：</label> <br>
<textarea id="usage_content" name="usage" readonly rows="6" cols="60"> <?php echo $row['usage_optional']?> </textarea>

<br><br>

<label id="note_label">备注：</label> <br>
<textarea id="note_content" name="note" readonly rows="6" cols="60"> <?php echo $row['note_optional']?> </textarea>

<br><br>

<label id="last_added_note_label">教师意见：</label> <br>
<textarea id="last_added_note_content" name="last_added_note" readonly rows="6" cols="60"> <?php echo $row['last_added_note']?> </textarea>

<br><br>

<input action="action" type="button" onclick="history.go(-1);" value="返回"/>

</body>

</html>
