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
