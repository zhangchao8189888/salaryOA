/**
 * Created by zhangchao8189888 on 15-1-24.
 */
$(function(){
    $("#e_company").on("click",function(){
        var input;
        var inputVal;
        var suggestWrap = $('#custor_search_suggest');
        var oSearchSelect = BaseWidget.UI.SearchSelect;
        oSearchSelect.fnInt();
        oSearchSelect.leftPlus = -200;
        oSearchSelect.topPlus = 76;
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
                        url: "index.php?action=Salary&mode=getSalaryTimeListJson",
                        data: {companyId : obj.id},
                        dataType: "json",
                        success: function(data){
                            $("#salTimeId").html();
                            $("#salTimeId").append('<option value="-1">选择工资月份</option>');
                            for(var i = 0; i < data.length; i++) {
                                $("#salTimeId").append('<option value="'+data[i].id+'">'+data[i].salaryTime+'</option>');
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