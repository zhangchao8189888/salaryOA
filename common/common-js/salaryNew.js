Array.prototype.remove=function(obj){
    for(var i =0;i <this.length;i++){
        var temp = this[i];
        if(!isNaN(obj)){
            temp=i;
        }
        if(temp == obj){
            for(var j = i;j <this.length;j++){
                this[j]=this[j+1];
            }
            this.length = this.length-1;
        }
    }
}
$(document).ready(function () {

    function createBigData() {
        var salTimeId = $('#salTimeId').val();
        $.ajax(
            {
                type: "get",
                url: "index.php?action=Salary&mode=getSalHeadJson",
                data: {salTimeId : salTimeId},
                dataType: "json",
                success: function(data){
                    var header = [];
                    //var columns = [];
                    for(var i = 1;i <= data[0].length; i++){
                        header.push(i);
//                        if (i >2 ) {
//                            columns.push({
//                                defaultValue:0,
//                                type: 'numeric',
//                                format: '0.00'
//                            });
//
//                        }else {
//                            columns.push({
//                                defaultValue:''
//                            });
//                        }
                    }
                    hot5.updateSettings({
                        //columns :columns,
                        colHeaders: header
                    });
                    hot5.loadData(data);

                }
            }
        );
    }
    var container = document.getElementById("exampleGrid");
    var hot5 = Handsontable(container, {
        data: [],
        startRows: 5,
        startCols: 4,
        colWidths: [55, 80, 200, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80], //can also be a number or a function
        rowHeaders: true,
        colHeaders: [],
        stretchH: 'last',
        defaultValue: '',
        manualColumnResize: true,
        manualRowResize: true,
        minSpareRows: 1,
        contextMenu: true
    });
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders'),
        reload = document.getElementById('reload');
    Handsontable.Dom.addEvent(reload,'click', function (){
        createBigData();
    });
    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = 'red';

    };
    var sumGrid = document.getElementById("sumGrid");
    var hot6 = Handsontable(sumGrid, {
        data: [],
        startRows: 5,
        startCols: 4,
        colWidths: [55, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80, 80], //can also be a number or a function
        rowHeaders: true,
        colHeaders: [],
        stretchH: 'last',
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        minSpareRows: 0,
        contextMenu: true
    });
    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            hot6.updateSettings({
                fixedColumnsLeft: 2
            });
        } else {
            hot6.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    var excelMove = [];
    var excelHead = [];
    var errorList = [];
    $('#sumFirst').click(function () {
        $.ajax({
            url: "index.php?action=Salary&mode=sumSalary",
            data: {
                shenfenzheng : $("#shenfenzheng").val(),
                add : $("#add").val(),
                del : $("#del").val(),
                freeTex : $("#freeTex").val(),
                data: hot5.getData()}, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.result === 'ok') {
                    var  salary = res.data;
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
                    errorList = res.error;
                    $("#error").html(errorList.length+"个错误");
                    $("#errorInfo").html("<tobdy></tobdy>");
                    for(var i =0 ; i < errorList.length; i++){
                        $("#errorInfo").append("<tr><td>"+errorList[i]['error']+"</td></tr>");
                    }
                    excelMove = res.move;

                    hot6.updateSettings({
                        colHeaders: excelHead
                    });
                    hot6.updateSettings({
                        colWidths: colWidths
                    });
                    hot6.loadData(salary);
                    hot6.updateSettings({
                        cells: function (row, col, prop) {
                            var cellProperties = {};
                            //console.log(hot6.getData()[row][6]);
                            if (hot6.getData()[row][col] == '无数值'){
                                //cellProperties.readOnly = true;
                                cellProperties.renderer = redRenderer;
                            }
                            return cellProperties;
                        }
                    })
                }
                else {
                    console.log('Save error');
                }
            },
            error: function () {
                console.log('Save error');
            }
        });

    });

    $("#save").click(function(){
        $('#modal-event1').modal({show:true});
    });
    $("#salarySave").click(function () {

        var data = hot6.getData();
        if (data.length < 0) {
            return;
        }
        if (errorList.length > 0) {
            alert('保存工资包含错误信息不能保存,请修改后重新保存');
            return;
        }
        var formData = {};
        var url = 'index.php?action=SaveSalary&mode=saveNewAddSalary';
        if ($("#change").val() == 1) {
            formData = {
                "data": data,
                company_id: $("#company_id").val(),
                e_company: $("#e_company").val(),
                salTimeId: $("#salTimeId").val(),
                mark:  $("#mark").val(),
                excelHead:  excelHead,
                excelMove : excelMove
            }
        }
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.code > 100000) {
                    console.text('Data saved');
                    alert(res.message);
                    return;
                }
                else {
                    alert(res.message);
                    window.location.href = "index.php?action=Salary&mode=salarySearchList";
                }
            },
            error: function () {
                console.text('Save error');
            }
        });
    });
    $("#e_company").on("click",function(){
        var input;
        var inputVal;
        var suggestWrap = $('#custor_search_suggest');
        var oSearchSelect = BaseWidget.UI.SearchSelect;
        oSearchSelect.fnInt();

        input = $(this);
        oSearchSelect.leftPlus = -185;
        oSearchSelect.topPlus = 64;
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
                            $("#salTimeId").html('<option value="-1">选择工资月份</option>');
                            for(var i = 0; i < data.length; i++) {
                                $("#salTimeId").append('<option value="'+data[i].id+'">'+data[i].salaryTime+'</option>');
                            }

                        }
                    }
                );
            }
        }
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
    createBigData();
});/**
 * Created by zhangchao8189888 on 15-1-3.
 */
