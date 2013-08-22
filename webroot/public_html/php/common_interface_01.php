<?php
require_once(dirname(__FILE__) . '/utils.php');
?>

<p>
<label id="request_status">申请目前状态：</label> <?php getGeneralStatusFromIndex($row['request_status']) ?>

<label id="id">申请流水号：</label> <input type="text" name="id" required readonly value="<?php echo $row['request_id']?>">
</p>

<p>
<label id="name">申请人姓名：</label> <input type="text" name="name" required readonly value="<?php echo $row['submitter_name']?>">

<label id="id_number">申请人学号：</label> <input type="text" name="id_number" readonly value="<?php echo $row['submitter_id_number']?>">
</p>

<fieldset class="fieldset-auto-width">
    <legend>日期</legend>

    <p>    
    <label id="date">提交：</label>
    <input type="date" name="date" required readonly value="<?php echo $row['date_start']?>">

    <label id="date1">负责老师通过：</label>
    <input type="date" name="date1" readonly value="<?php echo $row['date_advisor_agreed']?>">
    </p>

    <p>
    <label id="date2">网报完成：</label>
    <input type="date" name="date2" readonly value="<?php echo $row['date_net_report_finished']?>">

    <label id="date3">提交人完成：</label>
    <input type="date" name="date3" readonly value="<?php echo $row['date_student_finished']?>">
    </p>

    <p>
    <label id="date4">报销完成：</label>
    <input type="date" name="date4" readonly value="<?php echo $row['date_finished']?>">
    </p>
</fieldset>

<br>

<p>报销金额：<input type="number" name="amount" min=0.0 step=0.01 required readonly value="<?php echo $row['amount']?>">元</p>

<p>
是否有预算（有预算才可提交报销申请）？
<input type="radio" name="budget" onclick="budgetChanged(this);" disabled='disabled' value="yes" checked>有
<input type="radio" name="budget" onclick="budgetChanged(this);" disabled='disabled' value="no">没有
</p>

<p>
财务助理姓名：<input type="text" name="finance_assist_name" required readonly value="<?php echo $row['financial_assistant_name']?>">
</p>

<p>
单据数量：<input type="number" name="pages" min=0 step=1 required readonly value="<?php echo $row['page_number']?>">页
</p>

<p>
报销科目：
<select name="class" required disabled="disabled" onchange="subjectChanged(this)">
    <option <?php if($row['subject']==0) echo 'selected'?> value="equipment">设备</option>
    <option <?php if($row['subject']==1) echo 'selected'?> value="material">材料</option>
    <option <?php if($row['subject']==2) echo 'selected'?> value="meeting">会议</option>
    <option <?php if($row['subject']==3) echo 'selected'?> value="layout">版面</option>
    <option <?php if($row['subject']==4) echo 'selected'?> value="software">软件</option>
    <option <?php if($row['subject']==5) echo 'selected'?> value="travel">差旅</option>
    <option <?php if($row['subject']==6) echo 'selected'?> value="others">其他</option>
</select>

<br>

<label id="other_subject_label" <?php if($row['subject']!=6) echo 'style="display: none"'?>>请填写具体报销科目：</label>
<input id="other_subject_input" <?php if($row['subject']!=6) echo 'style="display: none"'?> type="text" name="other_subject" readonly value="<?php echo $row['subject_other']?>">
</p>

<fieldset class="fieldset-auto-width">
    <legend>特殊科目</legend>

    <label>是否为特殊科目？</label>
    <input type="radio" name="special" onclick="specialChanged(this);" disabled='disabled' value="yes" <?php if($row['is_special']==1) echo "checked"?>>是
    <input type="radio" name="special" onclick="specialChanged(this);" disabled='disabled' value="no" <?php if($row['is_special']==0) echo "checked"?>>不是

    <p>
    <label id="special_label_1" <?php if($row['is_special']==0) echo 'style="display: none"'?>>知识产权平台流水号：</label>
    <input id="special_input_1" <?php if($row['is_special']==0) echo 'style="display: none"'?> type="text" name="special_int_intel" readonly value="<?php echo $row['intel_platform_id']?>">
    </p>

    <p>
    <label id="special_label_2" <?php if($row['is_special']==0) echo 'style="display: none"'?>>资产平台流水号：</label>
    <input id="special_input_2" <?php if($row['is_special']==0) echo 'style="display: none"'?> type="text" name="special_int_asset" readonly value="<?php echo $row['asset_platform_id']?>">
    </p>
</fieldset>

<br><br>

报销材料是否齐全（齐全才可提交报销申请）？
<input type="radio" name="files" onclick="filesChanged(this);" disabled='disabled' value="yes" checked>是
<input type="radio" name="files" onclick="filesChanged(this);" disabled='disabled' value="no">否

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>合同内容</legend>
    <label id="company_name">公司名称：</label> <input type="text" name="company_name" readonly value="<?php echo $row['contract_company_name']?>">
    
    <label id="company_location">地区（城市）：</label>
    <input type="text" name="company_location" readonly value="<?php echo $row['contract_location']?>">

    <br>

    <label id="bank_card">银行卡号：</label> <input type="text" name="bank_card" readonly value="<?php echo $row['contract_bank_number']?>">

    <label id="opener">开户行：</label> <input type="text" name="opener" readonly value="<?php echo $row['contract_opener']?>">
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>发票内容</legend>

    <label>是否和实际内容一致？</label>
    <input type="radio" name="receipt_agree" onclick="receiptChanged(this);" disabled='disabled' value="yes" <?php if($row['receipt_same_as_actual']==1) echo "checked"?>>是
    <input type="radio" name="receipt_agree" onclick="receiptChanged(this);" disabled='disabled' value="no" <?php if($row['receipt_same_as_actual']==0) echo "checked"?>>不是

    <br>

    <label id="receipt_label" <?php if($row['receipt_same_as_actual']==1) echo 'style="display: none"'?>>请填写不一致的内容：</label>
    <textarea id="receipt_content" name="receipt_content" <?php if($row['receipt_same_as_actual']==1) echo 'style="display: none"'?> rows="6" cols="60" readonly> <?php echo $row['receipt_difference']?> </textarea>
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>负责老师</legend>
    
    <label>导师组组别：</label>
    <input type="text" name="professor_class" readonly value="<?php echo $row['professor_class']?>">

    <label id="professor_name_label">导师姓名：</label>
    <input type="text" name="professor_name" readonly value="<?php echo $row['professor_name']?>">
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>支出项目</legend>
    
    <label>经费卡编号：</label>
    <input type="text" name="card_number" required readonly value="<?php echo $row['expanse_number']?>">

    <label id="card_name_label">经费卡名称：</label>
    <input type="text" name="card_name" readonly value="<?php echo $row['expanse_name']?>">
</fieldset>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>支付方式</legend>
    
    <label>支付方式：</label>
    <select name="payment_option" required disabled="disabled" onchange="paymentChanged(this)">
        <option <?php if($row['payment_option']==0) echo 'selected'?> value="post">邮局代发</option>
        <option <?php if($row['payment_option']==1) echo 'selected'?> value="tele">汇款</option>
        <option <?php if($row['payment_option']==2) echo 'selected'?> value="cash">现金</option>
        <option <?php if($row['payment_option']==3) echo 'selected'?> value="check">支票</option>
        <option <?php if($row['payment_option']==4) echo 'selected'?> value="others">其他</option>
    </select>

    <label id="other_payment_label" <?php if($row['payment_option']!=4) echo 'style="display: none"'?>>请填写支付方式：</label>
    <input id="other_payment_input" <?php if($row['payment_option']!=4) echo 'style="display: none"'?> type="text" name="other_payment_option" readonly value="<?php echo $row['payment_option_other']?>">
</fieldset>

<br><br>

<label id="usage_label">用途：</label> <br>
<textarea id="usage_content" name="usage" readonly rows="6" cols="60"> <?php echo $row['usage_optional']?> </textarea>

<br><br>

<label id="note_label">备注：</label> <br>
<textarea id="note_content" name="note" readonly rows="6" cols="60"> <?php echo $row['note_optional']?> </textarea>

<br><br>

<fieldset class="fieldset-auto-width">
    <legend>转交</legend>

    <label>是否转交给其他财务助理？</label>
    <input type="radio" name="transfer_sel" onclick="transferChanged(this);" disabled='disabled' value="yes" <?php if(isset($row['transfered_username'])) echo "checked"?>>是
    <input type="radio" name="transfer_sel" onclick="transferChanged(this);" disabled='disabled' value="no" <?php if(!isset($row['transfered_username'])) echo "checked"?>>否

    <br>

    <label id="transfer_label" <?php if(!isset($row['transfered_username'])) echo 'style="display: none"'?>>请选择：</label>
    <select id="transfer" name="transfer" disabled='disabled' <?php if(!isset($row['transfered_username'])) echo 'style="display: none"'?> required>
    <?php
    while ($assit = mysql_fetch_array($assistants)) {
        if ($assit['username'] != $row['financial_assistant_username']) {
            echo '<option ';

            if (isset($row['transfered_username']) &&
                $assit['username'] == $row['transfered_username']) {
                echo 'selected="selected" ';
            }

            echo 'value="' . $assit['username'] . '">' .
                $assit['last_name'] . $assit['first_name'] .
                '</option>';
        }
    }
    ?>
    </select>
</fieldset>

<br><br>
