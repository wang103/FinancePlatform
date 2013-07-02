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

<label id="class">报销科目：</label>
<select name="class" required>
    <option value="equipment">设备</option>
    <option value="material">材料</option>
    <option value="meeting">会议</option>
    <option value="layout">版面</option>
    <option value="software">软件</option>
    <option value="travel">差旅</option>
    <option value="others">其他</option>
</select>

<br><br>
<input type="submit" value="提交报销申请">
</form>

</body>

</html>
