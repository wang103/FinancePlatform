<script>
function subjectChanged(sel) {
    var value = sel.options[sel.selectedIndex].value;
    if (value == "others") {
        document.getElementById("other_subject_label").style.display = 'block';
        document.getElementById("other_subject_input").style.display = 'block';
        document.getElementById("other_subject_input").required = true;
    } else {
        document.getElementById("other_subject_label").style.display = 'none';
        document.getElementById("other_subject_input").style.display = 'none';
        document.getElementById("other_subject_input").required = false;
    }
}

function receiptChanged(sel) {
    if (sel.value == "yes") {
        document.getElementById("receipt_label").style.display = 'none';
        document.getElementById("receipt_content").style.display = 'none';
        document.getElementById("receipt_content").required = false;
    } else {
        document.getElementById("receipt_label").style.display = 'block';
        document.getElementById("receipt_content").style.display = 'block';
        document.getElementById("receipt_content").required = true;
    }
}

function budgetChanged(sel) {
    var theButton = document.getElementById("submit_button");
    if (sel.value == "yes") {
        theButton.value = "提交报销申请";
        theButton.disabled = false;
    } else {
        theButton.value = "没有预算无法提交";
        theButton.disabled = true;
    }
}

function filesChanged(sel) {
    var theButton = document.getElementById("submit_button");
    if (sel.value == "yes") {
        theButton.value = "提交报销申请";
        theButton.disabled = false;
    } else {
        theButton.value = "材料不齐无法提交";
        theButton.disabled = true;
    }
}

function paymentChanged(sel) {
    var value = sel.options[sel.selectedIndex].value;
    if (value == "others") {
        document.getElementById("other_payment_label").style.display = 'block';
        document.getElementById("other_payment_input").style.display = 'block';
        document.getElementById("other_payment_input").required = true;
    } else {
        document.getElementById("other_payment_label").style.display = 'none';
        document.getElementById("other_payment_input").style.display = 'none';
        document.getElementById("other_payment_input").required = false;
    }
}
</script>

<html>

<head>
<link rel='stylesheet' type='text/css' href='../css/style01.css'>
</head>

<body>

<?php
session_start();
?>

<h3>新建报销申请</h3>

<form action="php/create_new_request.php" method="post">
<label id="name">姓名：</label> <input type="text" name="name" required readonly value="<?php echo $_SESSION['LAST_NAME'] . $_SESSION['FIRST_NAME']?>">

<label id="id_number">学号：</label> <input type="text" name="id_number" required readonly value="<?php echo $_SESSION['ID_NUMBER']?>">

<label id="date">日期：</label> <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>">

<br>

报销金额：<input type="number" name="amount" min=0.0 step=0.01 required>元

<br>

<label>是否有预算（有预算才可提交报销申请）？<label>
<input type="radio" name="budget" onclick="budgetChanged(this);" value="yes" checked>有
<input type="radio" name="budget" onclick="budgetChanged(this);" value="no">没有

<br>

财务助理姓名：<input type="text" name="finance_assist_name" required>

<br>

单据数量：<input type="number" name="pages" min=0 step=1 required>页

<br>

<label id="class">报销科目：</label>
<select name="class" required onchange="subjectChanged(this)">
    <option value="equipment">设备</option>
    <option value="material">材料</option>
    <option value="meeting">会议</option>
    <option value="layout">版面</option>
    <option value="software">软件</option>
    <option value="travel">差旅</option>
    <option value="others">其他</option>
</select>

<label id="other_subject_label" style="display: none">请填写具体报销科目：</label>
<input id="other_subject_input" style="display: none" type="text" name="other_subject">

<br>

<label>报销材料是否齐全（齐全才可提交报销申请）？<label>
<input type="radio" name="files" onclick="filesChanged(this);" value="yes" checked>是
<input type="radio" name="files" onclick="filesChanged(this);" value="no">否

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
    <textarea id="receipt_content" style="display: none" rows="6" cols="60"></textarea>
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>负责老师</legend>
    
    <label>导师组组别：</label>
    <input type="text" name="professor_class" required>

    <label>导师姓名：</label>
    <input type="text" name="professor_name" required>
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>支出项目</legend>
    
    <label>经费卡编号：</label>
    <input type="text" name="card_number" required>

    <label>经费卡名称：</label>
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

<label id="note_label">备注：</label> <br>
<textarea id="note_content" rows="6" cols="60"></textarea>

<br><br>

<input id="submit_button" type="submit" value="提交报销申请">
</form>

</body>

</html>
