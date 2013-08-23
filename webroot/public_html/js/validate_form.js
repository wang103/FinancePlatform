function validateModifyAccountForm() {
    var form = document.forms["modify_account_form"];

    var pw1 = form["new_pw"].value;
    var pw2 = form["new_pw_again"].value;

    if (pw1 != null && pw1 != "") {
        if (pw2 != pw1) {
            alert("两次新密码输入不同");
            return false;
        }
    }
}

var int_rule = 1;

function validateSubmitRequestForm(form_name) {
    var form = document.forms[form_name];

    var name = form["name"].value;
    if (name == null || name == "") {
        alert("申请人姓名不能为空");
        return false;
    }

    var amount = form["amount"].value;
    if (amount == null || amount == "") {
        alert("报销金额不能为空");
        return false;
    }

    var pages = form["pages"].value;
    if (pages == null || pages == "") {
        alert("单据数量不能为空");
        return false;
    }

    var r_class = form["class"].value;
    if (r_class == "others") {
        var other_subject = form["other_subject"].value;
        if (other_subject == null || other_subject == "") {
            alert("具体报销科目不能为空");
            return false;
        }
    }

    var card_number = form["card_number"].value;
    if (card_number == null || card_number == "") {
        alert("经费卡号不能为空");
        return false;
    }

    if (int_rule == 2) {
        var intel_number = form["special_int_intel"].value;
        if (intel_number == null || intel_number == "") {
            alert("知识产权平台流水号不能为空");
            return false;
        }
    } else if (int_rule == 1) {
        var asset_number = form["special_int_asset"].value;
        if (asset_number == null || asset_number == "") {
            alert("资产平台流水号不能为空");
            return false;
        }
    } else if (int_rule == 0) {
        var intel_number = form["special_int_intel"].value;
        var asset_number = form["special_int_asset"].value;
        
        if ((intel_number == null || intel_number == "") &&
                (asset_number == null || asset_number == "")) {
            alert("最少要填写一个平台的流水号");
            return false;
        }
    }

    /*** Enable the radio buttons so post can read its value ***/
    var elems = document.getElementsByName("special");
    for (i = 0; i < elems.length; i++) {
        elems[i].disabled = false;
    }
}
