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

    var elems = document.getElementsByName("special");
    for (i = 0; i < elems.length; i++) {
        elems[i].disabled = true;
    }

    // equipment shows asset number field.
    // layout shows intel-property number field.
    // material, software, and others show both field.
    // The rest shows nothing.
    if (value == "equipment") {
        turnOnSpecialSubject(1);
    } else if (value == "layout") {
        turnOnSpecialSubject(2);
    } else if (value == "material" || value == "software") {
        turnOnSpecialSubject(0);
    } else if (value == "others") {
        turnOnSpecialSubject(0);
        
        var elems = document.getElementsByName("special");
        for (i = 0; i < elems.length; i++) {
            elems[i].disabled = false;
        }
    } else {
        turnOffSpecialSubject();
    }
}

function specialChanged(sel) {
    if (sel.value == "no") {
        turnOffSpecialSubject();
    } else {
        turnOnSpecialSubject(0);
    }
}

/*---------- private function start ----------*/

/**
 * param option:    0->show both asset number and intell-property number.
 *                  1->show only asset number.
 *                  2->show only intell-property number.
 */
function turnOnSpecialSubject(option) {
    int_rule = option; 
    
    var intelElementLb = document.getElementById("special_label_1");
    var assetElementLb = document.getElementById("special_label_2");
    var intelElementIp = document.getElementById("special_input_1");
    var assetElementIp = document.getElementById("special_input_2");

    var elems = document.getElementsByName("special");
    for (i = 0; i < elems.length; i++) {
        if (elems[i].value == "yes") {
            elems[i].checked = true;
        } else {
            elems[i].checked = false;
        }
    }

    if (option == 1) {
        intelElementLb.style.display = 'none';
        assetElementLb.style.display = 'block';
        intelElementIp.style.display = 'none';
        assetElementIp.style.display = 'block';

        intelElementIp.disabled = true;
        assetElementIp.disabled = false;
    } else if (option == 2) {
        intelElementLb.style.display = 'block';
        assetElementLb.style.display = 'none';
        intelElementIp.style.display = 'block';
        assetElementIp.style.display = 'none';

        intelElementIp.disabled = false;
        assetElementIp.disabled = true;
    } else {
        intelElementLb.style.display = 'block';
        assetElementLb.style.display = 'block';
        intelElementIp.style.display = 'block';
        assetElementIp.style.display = 'block';
        
        intelElementIp.disabled = false;
        assetElementIp.disabled = false;
    }
}

function turnOffSpecialSubject() {
    int_rule = -1;

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
