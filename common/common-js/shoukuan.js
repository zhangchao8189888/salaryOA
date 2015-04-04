$(document).ready(function () {
    $("#company_validate").validate({
        onsubmit:true,
        submitHandler:function(form){
            form.submit();
        },
        rules: {
            company_name: { required: true },
            salaryDate: { required: true },
            file: { required: true }
        },
        messages: {
            company_name:
            {
                required: '必填'
            },
            salaryDate:
            {
                required: '必填'
            },
            file:
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
    $("#com_add").click(function(){
        /*$("#pro_date").val($("#shaijia_date").val());*/
        $.ajax(
            {
                type: "get",
                url: "index.php?action=Company&mode=getCode",
                data: {type:'fukuantongzhi'},
                dataType: "json",
                success: function(data){
                    $(".codeNo").text(data.codeNo);
                    $("#shouNo").val(data.codeNo);
                }
            }
        );
        $('#modal-event1').modal({show:true});
    });
    var container = document.getElementById("exampleGrid");
    var salaryGride = Handsontable(container, {
        data: [],
        startRows: 5,
        startCols: 4,
        colWidths: [], //can also be a number or a function
        rowHeaders: true,
        colHeaders: [],
        stretchH: 'last',
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        minSpareRows: 0,
        contextMenu: true
    });
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            salaryGride.updateSettings({
                fixedColumnsLeft: 2
            });
        } else {
            salaryGride.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    var excelHead = '';
    $('.rowCheck').click(function () {
        var salTimeId = $(this).attr('data-id');
        $("#salaryId").val(salTimeId);
        $.ajax({
            url: "index.php?action=Salary&mode=getSalaryListByTimeIdJson",
            data: {
                salTimeId : salTimeId
            }, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.code == 100000) {
                    var  salary = res.salary;
                    excelHead =  res.head;
                    var shenfenleibie = res['shenfenleibie'];
                    var colWidths = [];
                    for(var i = 0;i < excelHead.length; i++){
                        if (i == shenfenleibie) colWidths.push(160);
                        else if (i == excelHead.length-1) {colWidths.push(160);}
                        else {
                            colWidths.push(80);
                        }
                    }

                    salaryGride.updateSettings({
                        colHeaders: excelHead
                    });
                    salaryGride.updateSettings({
                        colWidths: colWidths
                    });
                    salaryGride.loadData(salary);
                }
                else {
                    console.log('get error');
                }
            },
            error: function () {
                console.log('ajax error');
            }
        });

    });
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');
    /*Handsontable.Dom.addEvent(rowHeaders, 'click', function () {
     hot5.updateSettings({
     rowHeaders: this.checked
     });
     });
     */
    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            hot5.updateSettings({
                fixedColumnsLeft: 2
            });
        } else {
            hot5.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    $("#import").click(function(){
        $("#excelForm").attr("action","index.php?action=Salary&mode=salaryImport");
        $("#excelForm").submit();
    });
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
function getSalarySumInfo() {
    $.ajax(
        {
            type: "get",
            url: "index.php?action=Salary&mode=getSalaryInfoJson",
            data: {salTime : $("#salTime").val()},
            dataType: "json",
            success: function(data){
                $("#yingfujine").val(data.sum_paysum_zhongqi);
                $("#laowufei").val(data.sum_laowufei);

            }
        }
    );
}/**
 * Created by zhangchao8189888 on 15-1-3.
 */
/**
 * Created by chaozhang204017 on 15-1-17.
 */
