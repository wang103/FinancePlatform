<?php

function isMyStudentsSubmission($studentUsername, $myUsername) {
    # Connect to the database.
    require_once(dirname(__FILE__) . '/../../config.php');

    $con1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
    if (!$con1) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db(DB_DATABASE, $con1);

    # Load advisor details.
    mysql_query('SET NAMES utf8');
    $result = mysql_query('SELECT * FROM advisors WHERE student_username="' . $studentUsername . '"');
    $row = mysql_fetch_array($result);

    mysql_close($con1);
    
    return $myUsername == $row['advisor_username'];
}

function getSubjectNameFromIndex($subjectIndex, $othersName) {
    if ($subjectIndex == 0) {
        echo "设备";
    } else if ($subjectIndex == 1) {
        echo "材料";
    } else if ($subjectIndex == 2) {
        echo "会议";
    } else if ($subjectIndex == 3) {
        echo "版面";
    } else if ($subjectIndex == 4) {
        echo "软件";
    } else if ($subjectIndex == 5) {
        echo "差旅";
    } else {
        echo $othersName;
    }
}

function getGeneralStatusFromIndex($statusIndex) {
    if ($statusIndex == 0) {
        echo "刚刚提交，等待负责老师同意";
    } else if ($statusIndex == 1) {
        echo "同意通过，等待老师完成网报";
    } else if ($statusIndex == 2) {
        echo "网报完成，等待申请人完成报销";
    } else if ($statusIndex == 3) {
        echo "报销完成，等待老师添加意见";
    } else if ($statusIndex == 4) {
        echo "此报销已完成";
    } else if ($statusIndex == 5) {
        echo "报销永久终止，负责老师没有同意";
    } else if ($statusIndex == 6) {
        echo "报销永久终止，财务主任没有同意";
    } else if ($statusIndex == 7) {
        echo "报销永久终止，财务助理取消申请";
    }
}

function getStatusFromIndex($statusIndex) {
    if ($statusIndex == 0) {
        if ($_SESSION['STATUS'] == 3) {
            echo "点此查看及同意报销";
        } else {
            echo "等待负责老师同意";
        }
    } else if ($statusIndex == 1) {
        if ($_SESSION['STATUS'] == 0) {
            echo "修改或者完成网报";
        } else {
            echo "等待老师完成网报";
        }
    } else if ($statusIndex == 2) {
        if ($_SESSION['STATUS'] == 1 || $_SESSION['STATUS'] == 2) {
            echo "点此完成报销";
        } else {
            echo "等待学生完成报销";
        }
    } else if ($statusIndex == 3) {
        if ($_SESSION['STATUS'] == 0) {
            echo "点此添加意见";
        } else {
            echo "等待老师添加意见";
        }
    } else if ($statusIndex == 4) {
        echo "此报销已完成";
    } else if ($statusIndex == 5) {
        echo "报销永久终止，负责老师没有同意";
    } else if ($statusIndex == 6) {
        echo "报销永久终止，财务主任没有同意";
    } else if ($statusIndex == 7) {
        echo "报销永久终止，财务助理取消申请";
    }
}

function notifyWithEmail($to, $status) {
    require_once(dirname(__FILE__) . '/../../config.php');

    $subject = "财务平台提醒：";
    $message = "请登录平台进行查看或处理：\n" .
        HOME_URL;

    if ($status == 0) {
        $subject .= "新的申请等待你的同意";
    } elseif ($status == 1) {
        $subject .= "新的申请等待你的网报";
    } elseif ($status == 2) {
        $subject .= "你的报销网报已经完成";
    } elseif ($status == 3) {
        $subject .= "有报销等待你添加意见";
    } elseif ($status == 4) {
        $subject .= "报销完成";
    } elseif ($status == 5) {
        $subject .= "有申请已被转交给你";
    } elseif ($status == 6) {
        $subject .= "你的申请已被转交";
    } elseif ($status == 7) {
        $subject .= "你的申请已被负责老师驳回";
    } elseif ($status == 8) {
        $subject .= "你的申请已被财务主任驳回";
    } elseif ($status == 9) {
        $subject .= "某报销申请被助理取消";
    } else {
        return 1;
    }

    $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    $header = 'MIME-Version: 1.0' . '\r\n' .
        'Content-type: text/html;' .
        'charset=UTF-8;' .
        'format=flowed' . '\r\n' .
        'Content-Transfer-Encoding: 8Bit\r\n' .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $header);

    return 0;
}

function makeLinks($string) {
    $content_array = explode(" ", $string);
    $result = '';

    foreach ($content_array as $content) {
        if (strcasecmp(substr($content, 0, 7), "http://") == 0 ||
            strcasecmp(substr($content, 0, 8), "https://") == 0 ||
            strcasecmp(substr($content, 0, 4), "www.") == 0) {

            if (strcasecmp(substr($content, 0, 4), "www.") == 0) {
                $content = "http://" . $content;
            }

            $content = '<a href="' . $content . '" target="_blank">' . $content . '</a>';
        }

        $result = $result . ' ' . $content;
    }
    
    $result = trim($result);
    return $result;
}
?>
