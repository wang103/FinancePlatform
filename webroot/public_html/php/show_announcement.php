<?php
session_start();
?>

<h3>公告栏</h3>

<?php
# Connect to the database.
require_once('../config.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load announcements.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM announcements ORDER BY announce_id DESC');

mysql_close($con);

$announce_empty = 1;
while ($row = mysql_fetch_array($result)) {
    echo '
    <p>
    <a href="php/show_detailed_announce.php?an=' . $row['announce_id'] .
    '" target="_blank">' . $row['title'] . '</a>
    <br><br>' .
    '最后发布/修改时间：<i>' . $row['date'] . ' by ' . $row['poster'] . '</i>' .
    '</p>';
    
    # If it's professor, allow modifying the post.
    if (isset($_SESSION['STATUS']) &&
        ($_SESSION['STATUS'] == 0 || $_SESSION['STATUS'] == 3)) {
        # Pass the announcement id to php page using GET.
        echo '
            <input type="button" value="修改" class="btn" ' .
            'onClick="location=\'php/show_modify_announce.php?an=' .
            $row['announce_id'] . '\';"/>
            
            <input type="button" value="删除" class="btn" ' .
            'onClick="if(confirm(\'确定删除？\'))location=\'php/delete_announcement.php?an=' .
            $row['announce_id'] . '\';return false;"/>
        ';
    }
    
    echo '<hr>';
    $announce_empty = 0;
}

if ($announce_empty == 1) {
    echo '
    <p>没有任何公告</p>
    ';
}
?>

<!--If professor, show the new announcement entry-->
<?php
if (isset($_SESSION['STATUS']) &&
    ($_SESSION['STATUS'] == 0 || $_SESSION['STATUS'] == 3)) {
    echo '
    <hr style="height:5px;">
    <form action="php/insert_announcement.php" method="post">
    <p>
    发布新公告：<br>
    主题：<br> <input type="text" name="title" required> <br>
    内容：<br>
    <textarea name="content" rows="6" cols="60" required></textarea>
    <br>
    <input type="submit" value="发布新公告"/>
    <br>
    </p>
    </form>';
}
?>
