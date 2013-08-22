function subjectChanged(sel) {
    var value = sel.options[sel.selectedIndex].value;
    if (value == "others") {
        document.getElementById("other_subject_label").style.display = 'block';
        document.getElementById("other_subject_input").style.display = 'block';
        document.getElementById("other_subject_input").required = true;
    } else {
        document.getElementById("other_subject_label").style.display = 'none';
        document.getElementById("other_subject_input").style.display = 'none';
        document.getElementById("other_subject_input").required = false;
    }

    if (value == "equipment" || value == "material" ||
            value == "software" || value == "others") {
        turnOnSpecialSubject();
    } else {
        turnOffSpecialSubject();
    }
}

function specialChanged(sel) {
    if (sel.value == "no") {
        turnOffSpecialSubject();
    } else {
        turnOnSpecialSubject();
    }
}

/*---------- private function start ----------*/

function turnOnSpecialSubject() {
    document.getElementById("special_label_1").style.display = 'block';
    document.getElementById("special_label_2").style.display = 'block';
    document.getElementById("special_input_1").style.display = 'block';
    document.getElementById("special_input_2").style.display = 'block';
    
    var elems = document.getElementsByName("special");
    for (i = 0; i < elems.length; i++) {
        if (elems[i].value == "yes") {
            elems[i].checked = true;
        } else {
            elems[i].checked = false;
        }
    }
}

function turnOffSpecialSubject() {
    document.getElementById("special_label_1").style.display = 'none';
    document.getElementById("special_label_2").style.display = 'none';
    document.getElementById("special_input_1").style.display = 'none';
    document.getElementById("special_input_2").style.display = 'none';
    
    var elems = document.getElementsByName("special");
    for (i = 0; i < elems.length; i++) {
        if (elems[i].value == "yes") {
            elems[i].checked = false;
        } else {
            elems[i].checked = true;
        }
    }
}

/*---------- private function end ----------*/

function transferChanged(sel) {
    if (sel.value == "no") {
        document.getElementById("transfer_label").style.display = 'none';
        document.getElementById("transfer").style.display = 'none';
        document.getElementById("transfer").required = false;
    } else {
        document.getElementById("transfer_label").style.display = 'block';
        document.getElementById("transfer").style.display = 'block';
        document.getElementById("transfer").required = true;
    }
}

var passBudget = true;
var passFiles = true;

function budgetChanged(sel) {
    var theButton = document.getElementById("submit_button");
    if (sel.value == "yes") {
        if (passFiles) {
            theButton.value = "提交报销申请";
            theButton.disabled = false;
        } else {
            theButton.value = "材料不齐无法提交";
            theButton.disabled = true;
        }
        passBudget = true;
    } else {
        theButton.value = "没有预算无法提交";
        theButton.disabled = true;
        passBudget = false;
    }
}

function filesChanged(sel) {
    var theButton = document.getElementById("submit_button");
    if (sel.value == "yes") {
        if (passBudget) {
            theButton.value = "提交报销申请";
            theButton.disabled = false;
        } else {
            theButton.value = "没有预算无法提交";
            theButton.disabled = true;
        }
        passFiles = true;
    } else {
        theButton.value = "材料不齐无法提交";
        theButton.disabled = true;
        passFiles = false;
    }
}

function paymentChanged(sel) {
    var value = sel.options[sel.selectedIndex].value;
    if (value == "others") {
        document.getElementById("other_payment_label").style.display = 'block';
        document.getElementById("other_payment_input").style.display = 'block';
        document.getElementById("other_payment_input").required = true;
    } else {
        document.getElementById("other_payment_label").style.display = 'none';
        document.getElementById("other_payment_input").style.display = 'none';
        document.getElementById("other_payment_input").required = false;
    }
}
