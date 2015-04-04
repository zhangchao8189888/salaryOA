/**
 * Created by chaozhang204017 on 14-12-25.
 */
$(function(){
    $("#company_validate").validate({
        onsubmit:true,
        submitHandler:function(form){
            if (!$("#company_id").val()) {
                alert('请重新选择公司');
                return;
            }
            var obj = {};
            obj.e_num = $("#e_num").val();
            obj.e_company = $("#e_company").val();
            obj.company_id = $("#company_id").val();
            obj.e_name = $("#e_name").val();
            obj.bank_no = $("#bank_no").val();
            obj.e_bank = $("#e_bank").val();
            obj.e_type = $("#e_type").val();
            obj.e_state = $("#e_state").val();
            obj.employ_id = $("#employ_id").val();
            obj.shebaojishu = $("#shebaojishu").val();
            obj.gongjijinjishu = $("#gongjijinjishu").val();
            obj.canbaojin = $("#canbaojin").val();
            obj.laowufei = $("#laowufei").val();
            obj.danganfei = $("#danganfei").val();
            obj.e_hetongnian = $("#e_hetongnian").val();
            obj.e_hetong_date = $("#e_hetong_date").val();
            obj.department_id = $("#department").val();
            obj.memo = $("#memo").val();
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Employ&mode=saveOrUpdateEmploy",
                    data: obj,
                    dataType :'json',
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
            e_name: { required: true },
            e_company: { required: true },
            e_hetongnian: { required: true },
            e_hetong_date: { required: true },
            e_name: { required: true }
            /*contacts_no: { required: true },
             contacts: { required: true }*/
            /*company_name:
             {
             required: true,
             remote:{                                          //验证用户名是否存在
             type:"POST",
             url:"index.php?action=Customer&mode=verifyCustomCode",             //servlet
             data:{
             code:function(){return $("#customer_code").val();}
             }
             }
             },
             txbNewPwd2: { required: true, rangelength: [8, 15], equalTo: "#txbNewPwd1" }*/
        },
        messages: {
            company_name:
            {
                required: '必填'
            },
            contacts:
            {
                required: '必填'
            },
            e_hetongnian:
            {
                required: '必填'
            },
            e_hetong_date:
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
    $("#e_company").on("click",function(){
        var input;
        var inputVal;
        var suggestWrap = $('#custor_search_suggest');
        var oSearchSelect = BaseWidget.UI.SearchSelect;
        oSearchSelect.fnInt();
        oSearchSelect.leftPlus = -179;
        oSearchSelect.topPlus = 75;
        oSearchSelect.inputWith = 314;
        oSearchSelect.url = 'index.php?action=Company&mode=getCompanyListJson';
        var fnHideSuggest = function(){
            var that = BaseWidget.UI.SearchSelect;
            that.inputVal = '';
            that.targetSuggestWrap.hide();
        }
        oSearchSelect.targetSuggestWrap = suggestWrap;
        oSearchSelect.fnHideSuggest = fnHideSuggest;
        oSearchSelect.fnMousedown = function (that,obj) {
            if (that.inputVal == obj.name) {
                that.fnHideSuggest();
            } else {
                //Customer.oCustomer.fnGetCustomerInfo(obj);
                //得到用户信息
                $("#e_company").val(obj.name);
                $("#company_id").val(obj.id);
                $.ajax(
                    {
                        type: "get",
                        url: "index.php?action=BaseData&mode=getDepartmentByComId",
                        data: {companyId : obj.id},
                        dataType: "json",
                        success: function(data){
                            $("#department").html('');
                            $("#department").append('<option value="0">无部门</option>');
                            for(var i = 0; i < data.length; i++) {
                                $("#department").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
                            }

                        }
                    }
                );
            }
        }
        input = $(this);
        oSearchSelect.targetInput = input;
        input.click(function(e){
            oSearchSelect.fnSendKeyWord(e);
        });
        input.keyup(
            function (e) {
                oSearchSelect.fnSendKeyWord(e);
            }
        );
        input.blur(oSearchSelect.fnHideSuggest);
        if (input.val() == '') {
            oSearchSelect.fnSendKeyWord({});
        } else {
            inputVal = input.val();
        }
        oSearchSelect.inputVal = inputVal;
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
