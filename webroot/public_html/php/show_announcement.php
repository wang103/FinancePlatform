<h3>公告栏</h3>

<?php
# Connect to the database.
require('../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load announcements.


mysql_close($con);
?>

<!--If professor, show the new announcement entry-->
<?php
session_start();
if (isset($_SESSION['STATUS']) && $_SESSION['STATUS'] == 0) {
    echo '
    <form action="php/insert_announcement.php" method="post">
    <p>
    公告内容：<br>
    <textarea name="content" rows="6" cols="60" required></textarea>
    <br>
    <input type="submit" value="发布新公告"/>
    <br>
    </p>
    </form>';
}
?>
