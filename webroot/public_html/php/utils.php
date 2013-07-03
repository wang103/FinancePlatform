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
        echo othersName;
    }
}
?>
