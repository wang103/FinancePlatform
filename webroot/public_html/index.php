<?php
header('Content-type: text/html; charset=utf-8');
session_start();
?>

<html>

<head>
<title>北邮报销申请 - 主页</title>
<link rel='stylesheet' type='text/css' href='css/style00.css'>
<script src='js/utils.js'></script>

<script>
var isPlaying = false;
document.onkeydown = function(evt) {
    evt = evt || window.event;
    if (evt.keyCode == 27) {
        var nyanElem = document.getElementById("nyan_div");
        if (isPlaying) {
            nyanElem.style.display = 'none';
            isPlaying = false;
        } else {
            nyanElem.style.display = 'block';
            isPlaying = true;
        }
    }
};
</script>

</head>

<body>

<!--Store the last visited page-->
<?php
$temp = explode("?", $_SERVER['REQUEST_URI']);
$_SESSION['last_url'] = $temp[0];
?>

<h1>北邮报销申请 - 主页</h1>

<div id='nyan_div' style="display: none">
<object data="video/nyan.swf" height="500" width="500">
<embed scr="video/nyan.swf" height="500" width="500">
</object>
</div>

<div id='container'>

<!--Middle: Current Page's Contents-->
<div id='layout_middle' class='column'>
<?php
include './php/show_announcement.php';
?>
</div>

<!--Left: Navigation Bar-->
<div id='layout_left' class='column'>
<?php
include './show_navi.html';
?>
</div>

<!--Right: Log In-->
<div id='layout_right' class='column'>
<?php
include './php/show_login.php';
?>
</div>

</div>

<?php
if (isset($_SESSION['feedback']) && $_SESSION['feedback'] == 7) {
    echo '
    <script>
        alert("账户修改成功！");
    </script>';
    unset($_SESSION['feedback']);
}
?>

</body>

</html>
