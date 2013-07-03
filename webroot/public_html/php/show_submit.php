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
<label id="name">姓名：</label> <input type="text" name="name" required readonly value="<?php echo $_SESSION['LAST_NAME'] . $_SESSION['FIRST_NAME']?>" >

<label id="date">日期：</label> <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" >

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

<fieldset class="fieldset-auto-width">
    <legend>发票内容</legend>

</fieldset>

<br><br>

<input type="submit" value="提交报销申请">
</form>

</body>

</html>
