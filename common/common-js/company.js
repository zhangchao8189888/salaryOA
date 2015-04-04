/**
 * Created by chaozhang204017 on 14-12-25.
 */
$(function(){
    $("#company_validate").validate({
        onsubmit:true,
        submitHandler:function(form){
            var obj = {};
            obj.company_id = $("#company_id").val();
            obj.company_code = $(".codeNo").text();
            obj.company_name = $("#company_name").val();
            obj.contacts = $("#contacts").val();
            obj.contacts_no = $("#contacts_no").val();
            obj.com_address = $("#com_address").val();
            obj.com_bank = $("#com_bank").val();
            obj.bank_no = $("#bank_no").val();
            obj.company_level = $("#company_level").val();
            obj.company_type = $("#company_type").val();
            obj.company_status = $("#company_status").val();
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Company&mode=saveOrUpdateCompany",
                    data: obj,
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        }
                        window.location.reload();
                    }
                }
            );
        },
        rules: {
            //company_name: { required: true },
            company_name:
            {
                required: true,
                remote:{                                          //验证用户名是否存在
                    type:"POST",
                    url:"index.php?action=Company&mode=verifyCompanyName",             //servlet
                    data:{
                        comName:function(){
                            if ($('#company_id').val()) {

                                return 'false';
                            }
                            return $("#company_name").val();
                        }
                    }
                }
            },
            contacts: { required: true },
            contacts_no: { required: true },
            bank_no : {
                remote:{                                          //验证用户名是否存在
                    type:"POST",
                    url:"index.php?action=Company&mode=verifyCompanyName",             //servlet
                    data:{
                        bank_no:function(){
                            if ($('#bank_no').val()) {

                                return 'false';
                            }
                            return $("#bank_no").val();
                        }
                    }
                }
            }
            /*contacts_no: { required: true },
            contacts: { required: true }*/

            /*txbNewPwd2: { required: true, rangelength: [8, 15], equalTo: "#txbNewPwd1" }*/
        },
        messages: {
            company_name:
            {
                required: '必填',
                remote : '公司名称已经存在'
            },
            contacts:
            {
                required: '必填'
            },
            contacts:
            {
                required: '必填'
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('success');
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }


    });

});



jQuery.extend(jQuery.validator.messages, {
    required: "必选字段",
    remote: "请修正该字段",
    email: "请输入正确格式的电子邮件",
    url: "请输入合法的网址",
    date: "请输入合法的日期",
    dateISO: "请输入合法的日期 (ISO).",
    number: "请输入合法的数字",
    digits: "只能输入整数",
    creditcard: "请输入合法的信用卡号",
    equalTo: "请再次输入相同的值",
    accept: "请输入拥有合法后缀名的字符串",
    maxlength: jQuery.validator.format("请输入一个 长度最多是 {0} 的字符串"),
    minlength: jQuery.validator.format("请输入一个 长度最少是 {0} 的字符串"),
    rangelength: jQuery.validator.format("请输入 一个长度介于 {0} 和 {1} 之间的字符串"),
    range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
    max: jQuery.validator.format("请输入一个最大为{0} 的值"),
    min: jQuery.validator.format("请输入一个最小为{0} 的值")
});
