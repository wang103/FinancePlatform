<h3>公告栏</h3>

<?php
session_start();

# Connect to the database.
require_once('../config.php');
require_once('php/utils.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load announcements.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM announcements ORDER BY announce_id DESC');

$announce_empty = 1;
while ($row = mysql_fetch_array($result)) {
    $content = wordwrap($content, 84, ' <br> ', false);
    $content = makeLinks($row['content']);
    
    echo '
    <p>主题：' . $row['title'] . '<br>' .
    $content . '<br><br>' .
    '最后发布/修改时间：<i>' . $row['date'] . ' by ' . $row['poster'] . '</i>' .
    '</p>';
    
    # If it's professor, allow modifying the post.
    if (isset($_SESSION['STATUS']) && $_SESSION['STATUS'] == 0) {
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
