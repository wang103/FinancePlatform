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
$result = mysql_query('SELECT * FROM announcements ORDER BY announce_id DESC');

$announce_empty = 1;
while ($row = mysql_fetch_array($result)) {
    echo '
    <p>' .
    $row['content'] . '<br>' .
    '<i>' . $row['date'] . ' by ' . $row['poster'] . '</i>' .
    '</p>';
    echo '<hr>';
    $announce_empty = 0;

    # If it's professor, allow modifying the post.

}

if ($announce_empty == 1) {
    echo '
    <p>没有任何公告</p>
    ';
}

mysql_close($con);
?>

<!--If professor, show the new announcement entry-->
<?php
session_start();
if (isset($_SESSION['STATUS']) && $_SESSION['STATUS'] == 0) {
    echo '
    <hr style="height:5px;">
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
