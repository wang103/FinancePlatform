<?php
session_start();
?>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style01.css'>
<script src='js/interface_listener.js'></script>
</head>

<body>

<?php
require_once('../config.php');
?>

<fieldset class="fieldset-auto-width">
    <legend>快速链接</legend>
    <a href=<?php echo '"' . INTEL_PLATFORM_URL . '"'?> target="_blank">查询知识产权平台</a>
    <a href=<?php echo '"' . ASSET_PLATFORM_URL . '"'?> target="_blank">查询资产平台</a>
</fieldset>

<h3>新建报销申请</h3>

<form action="php/create_new_request.php" method="post">
<label id="name">姓名：</label> <input type="text" name="name" required readonly value="<?php echo $_SESSION['LAST_NAME'] . $_SESSION['FIRST_NAME']?>">

<label id="id_number">学号：</label> <input type="text" name="id_number" required readonly value="<?php echo $_SESSION['ID_NUMBER']?>">

<label id="date">日期：</label> <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>">

<br>

<p>报销金额：<input type="number" name="amount" min=0.0 step=0.01 required>元</p>

<p>
是否有预算（有预算才可提交报销申请）？
<input type="radio" name="budget" onclick="budgetChanged(this);" value="yes" checked>有
<input type="radio" name="budget" onclick="budgetChanged(this);" value="no">没有
</p>

<p>
财务助理姓名：<input type="text" name="finance_assist_name" required>
</p>

<p>
单据数量：<input type="number" name="pages" min=0 step=1 required>页
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

<fieldset class="fieldset-auto-width">
    <legend>特殊科目</legend>

    <label>是否为特殊科目？</label>
    <input type="radio" name="special" onclick="specialChanged(this);" value="yes" checked>是
    <input type="radio" name="special" onclick="specialChanged(this);" value="no">不是

    <p>
    <label id="special_label_1">知识产权平台流水号：</label>
    <input id="special_input_1" type="text" name="special_int_intel">
    </p>

    <p>
    <label id="special_label_2">资产平台流水号：</label>
    <input id="special_input_2" type="text" name="special_int_asset">
    </p>
</fieldset>

<br><br>

报销材料是否齐全（齐全才可提交报销申请）？
<input type="radio" name="files" onclick="filesChanged(this);" value="yes" checked>是
<input type="radio" name="files" onclick="filesChanged(this);" value="no">否

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>合同内容</legend>
    <label id="company_name">公司名称：</label> <input type="text" name="company_name">
    
    <label id="company_location">地区（城市）：</label>
    <input type="text" name="company_location" value="北京">

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
</form>

<?php
if (isset($_SESSION['feedback']) && $_SESSION['feedback'] == 1) {
    echo '
    <script>
        alert("提交报销申请成功！");
    </script>';
    unset($_SESSION['feedback']);
}
?>

</body>

</html>
