<?php
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

function getStatusFromIndex($statusIndex) {
    session_start();

    if ($statusIndex == 0) {
        if ($_SESSION['STATUS'] == 0) {
            echo "修改或者完成网报";
        } else {
            echo "等待老师完成网报";
        }
    } else if ($statusIndex == 1) {
        if ($_SESSION['STATUS'] == 0) {
            echo "等待学生完成报销";
        } else {
            echo "点此完成报销";
        }
    } else if ($statusIndex == 2) {
        if ($_SESSION['STATUS'] == 0) {
            echo "点此添加意见";
        } else {
            echo "等待老师添加意见";
        }
    } else {
        echo "此报销已完成";
    }
}

function notifyWithEmail($from, $to, $subject, $message) {
    $headers = "From:" . $from;
    mail($to, $subject, $message, $headers);

    return 0;
}
?>
