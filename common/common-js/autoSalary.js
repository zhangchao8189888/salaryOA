/**
 * Created by zhangchao8189888 on 15-4-8.
 */
$(function(){
    var makeSalData;
    $("#salMake").click(function () {
        getSaldata();
    });

    var container = document.getElementById("exampleGrid");
    var hot5 = Handsontable(container, {
        data: [],
        stretchH: 'last',
        manualColumnResize: true,
        manualRowResize: true,
        contextMenu: true,
        rowHeaders: true,
        colHeaders: true,
        colWidths: [55, 80, 80, 80, 80, 80, 80],
        manualColumnMove: false,
        manualRowMove: true,
        minSpareRows: 1,
        afterChange : function (change, source) {
            if (source === 'loadData' || source === 'updateData' ) {
                return; //don't save this change
            }
//            console.log('Autosaved (' + change.length + ' ' + 'cell' + (change.length > 1 ? 's' : '') + ')');
            //console.log(change);
//            console.log(source);

            for(var val in change) {
                if (UTIL.checkRate(val) && change[val]) {
                    if (change[val][2] != change[val][3]) {
                        var row = parseInt(change[val][0]);
                        var col = parseInt(change[val][1]);
                        if (!UTIL.checkRate(change[val][3])) {
                            hot5.setDataAtCell( row, col, change[val][2] , "updateData");
                        }
                    }

                }
            }
        },
        afterRowMove:function  (oldIndex, newIndex) {
            var data = hot6.getData();
            $.ajax(
                {
                    type: "get",
                    url: "index.php?action=Employ&mode=modifyEmploySort",
                    data: {
                        rowData : data
                        //new_index : (newIndex+1),
                        //e_num : e_num

                    },
                    dataType: "json",
                    success: function(data){

                    }
                }
            );
        },
        persistentState: true
    });
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders'),
        reload = document.getElementById('reload');
    Handsontable.Dom.addEvent(reload,'click', function (){
        if(makeSalData){
            getSaldata();
        }
    });
    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = 'red';

    };
    function getSaldata(){
        var aa = '';
        $("input[name='ziduanId']:checkbox:checked").each(function(){
            aa+=$(this).val()+",";
        })
        if (aa == '') {
            alert('选择字段');
            return;
        }
        $("#ziuanIds").val(aa);
        var salaryDate = $("#salaryDate").val();
        $.ajax(
            {
                type: "get",
                url: "index.php?action=Salary&mode=getSalListJson",
                data: {
                    ziduanIds : aa,
                    salaryDate : salaryDate

                },
                dataType: "json",
                success: function(data){
                    var jData = data.data;
                    makeSalData = jData;
                    var head = data.head;
                    var headWith = data.headWith;
                    if(data.isDianfu){
                        $("#isDianfu").html("上个月有垫付");
                    } else {
                        $("#isDianfu").html("");
                    }
                    hot5.updateSettings({
                        colHeaders: head
                    });
                    var sumWith = 250;

                    for (var i =0;i < headWith.length;i++) {
                        sumWith+= headWith[i];
                    }
                    $('#exampleGrid').css('width',sumWith);
                    hot5.updateSettings({
                        colWidths: headWith
                    });
                    hot5.loadData(jData);

                }
            }
        );
    }

    /******计算完成列表*****/
    var sumGrid = document.getElementById("sumGrid");
    var hot6 = Handsontable(sumGrid, {
        data: [],
        startRows: 5,
        startCols: 4,
        colWidths: [], //can also be a number or a function
        rowHeaders: true,
        colHeaders: [],
        stretchH: 'last',
        manualColumnResize: true,
        manualRowResize: true,
        manualColumnMove: false,
        manualRowMove: true,
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
    /******计算工资*****/
    var excelMove = [];
    var excelHead = [];
    var errorList = [];
    $('#sumFirst').click(function () {
        $.ajax({
            url: "index.php?action=Salary&mode=autoSumSalary",
            data: {
                ziduan: hot5.getColHeader(),
                data: hot5.getData()

            }, //returns all cells' data
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
                    hot6.updateSettings({
                        manualRowMove: false
                    });
                    hot6.loadData(salary);
                    hot6.updateSettings({
                        cells: function (row, col, prop) {
                            var cellProperties = {};
                            //console.log(hot6.getData()[row][6]);
                            if (hot6.getData()[row][shenfenleibie] == 'null' || hot6.getData()[row][shenfenleibie] == null){
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


    /******保存工资*****/
    $("#save").click(function(){
        $("#saveSalDate").val($("#salaryDate").val());
        $('#modal-event1').modal({show:true});
    });
    $("#salarySave").click(function () {

        var data = hot6.getData();
        if (data.length < 0) {
            return;
        }
        var formData = {};
        var url = 'index.php?action=SaveSalary&mode=saveSalary';
        if (errorList.length > 0) {
            alert('请先解决错误再保存');
            return;
        }else {
            formData = {
                "data": data,
                company_id: $("#company_id").val(),
                e_company: $("#e_company").val(),
                salaryDate: $("#saveSalDate").val(),
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
                    alert(res.mess);
                    return;
                }
                else {
                    alert(res.mess);
                    window.location.href = "index.php?action=Salary&mode=salarySearchList";
                }
            },
            error: function () {
                console.text('Save error');
            }
        });
    });
});
