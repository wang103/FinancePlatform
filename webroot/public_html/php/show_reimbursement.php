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

<p>
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
<select name="class" required onchange="subjectChanged(this)">
    <option value="equipment">设备</option>
    <option value="material">材料</option>
    <option value="meeting">会议</option>
    <option value="layout">版面</option>
    <option value="software">软件</option>
    <option value="travel">差旅</option>
    <option value="others">其他</option>
</select>

<br>

<label id="other_subject_label" style="display: none">请填写具体报销科目：</label>
<input id="other_subject_input" style="display: none" type="text" name="other_subject">
</p>

报销材料是否齐全（齐全才可提交报销申请）？
<input type="radio" name="files" onclick="filesChanged(this);" readonly value="yes" checked>是
<input type="radio" name="files" onclick="filesChanged(this);" readonly value="no">否

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>合同内容</legend>
    <label id="company_name">公司名称：</label> <input type="text" name="company_name">
    
    <label id="company_location">地区：</label>
    <select name="company_location">
        <option value="beijing">北京</option>
        <option value="remote">外地</option>
        <option value="others">其他</option>
    </select>
    
    <br>

    <label id="bank_card">银行卡号：</label> <input type="text" name="bank_card">

    <label id="opener">开户行：</label> <input type="text" name="opener">
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>发票内容</legend>

    <label>是否和实际内容一致？</label>
    <input type="radio" name="receipt_agree" onclick="receiptChanged(this);" value="yes" checked>是
    <input type="radio" name="receipt_agree" onclick="receiptChanged(this);" value="no">不是

    <br>

    <label id="receipt_label" style="display: none">请填写不一致的内容：</label>
    <textarea id="receipt_content" name="receipt_content" style="display: none" rows="6" cols="60"></textarea>
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>负责老师</legend>
    
    <label>导师组组别：</label>
    <input type="text" name="professor_class" required>

    <label id="professor_name_label">导师姓名：</label>
    <input type="text" name="professor_name" required>
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>支出项目</legend>
    
    <label>经费卡编号：</label>
    <input type="text" name="card_number" required>

    <label id="card_name_label">经费卡名称：</label>
    <input type="text" name="card_name" required>
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>支付方式</legend>
    
    <label>支付方式：</label>
    <select name="payment_option" required onchange="paymentChanged(this)">
        <option value="post">邮局代发</option>
        <option value="tele">汇款</option>
        <option value="cash">现金</option>
        <option value="check">支票</option>
        <option value="others">其他</option>
    </select>

    <label id="other_payment_label" style="display: none">请填写支付方式：</label>
    <input id="other_payment_input" style="display: none" type="text" name="other_payment_option">
</fieldset>

<br><br>

<label id="usage_label">用途：</label> <br>
<textarea id="usage_content" name="usage" rows="6" cols="60"></textarea>

<br><br>

<label id="note_label">备注：</label> <br>
<textarea id="note_content" name="note" rows="6" cols="60"></textarea>

<br><br>

<input id="submit_button" type="submit" value="提交报销申请">

<?php
if (isset($_GET['status']) && $_GET['status'] == 1) {
    echo '
    <script>
        alert("提交报销申请成功！");
    </script>';
}
?>

</body>

</html>
