<html>

<?php
# Connect to the database.
require_once('../../config.php');
require_once('utils.php');

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB_DATABASE, $con);

# Load the announcement.
mysql_query('SET NAMES utf8');
$result = mysql_query('SELECT * FROM announcements WHERE announce_id = ' .
    $_GET['an'] . ' ORDER BY announce_id DESC');

mysql_close($con);

$row = mysql_fetch_array($result);

$content = nl2br($row['content']);
$content = makeLinks($content);
?>

<head>
<title><?php echo $row['title']?></title>
</head>

<body>

<h2><?php echo $row['title']?></h2>

<p><?php echo $content?></p>

<hr>

<p>
最后发布/修改时间：<i><?php echo $row['date'] . ' by ' . $row['poster']?></i>
</p>

</body>

</html>
