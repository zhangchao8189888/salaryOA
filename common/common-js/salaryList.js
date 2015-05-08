/**
 * Created by chaozhang204017 on 15-1-15.
 */
$(document).ready(function () {
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
    var dianfuGrid = document.getElementById("dianfuGrid");
    var dianfu = new Handsontable(dianfuGrid,{
        data: [],
        startRows: 5,
        startCols: 4,
        colHeaders: true,
        dataSchema: {e_num: '', per_shiye: {first: null, last: null}, address: null},
        colHeaders: ['姓名','身份证号', '个人失业', '个人医疗', '个人养老',
            '个人公积金', '单位失业', '单位医疗', '单位养老', '单位工伤',
            '单位生育','单位公积金'
        ],
        colWidths: [100,160, 100, 100, 100, 100, 100, 100, 100, 100, 100],
        columns: [
            {data: "e_name"},
            {data: "e_num"},
            {data: "per_shiye"},
            {data: "per_yiliao"},
            {data: "per_yanglao"},
            {data: "per_gongjijin"},
            {data: "com_shiye"},
            {data: "com_yiliao"},
            {data: "com_yanglao"},
            {data: "com_gongshang"},
            {data: "com_shengyu"},
            {data: "com_gongjijin"}
        ],
        stretchH: 'last',
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        minSpareRows: 0,
        contextMenu: true
    });
    var yanfuGrid = document.getElementById("yanfuGrid");
    var yanfu = new Handsontable(yanfuGrid,{
        data: [],
        startRows: 5,
        startCols: 4,
        colHeaders: true,
        dataSchema: {e_num: '', per_shiye: {first: null, last: null}, address: null},
        colHeaders: ['姓名','身份证号', '个人失业', '个人医疗', '个人养老',
            '个人公积金', '单位失业', '单位医疗', '单位养老', '单位工伤',
            '单位生育','单位公积金'
        ],
        colWidths: [100,160, 100, 100, 100, 100, 100, 100, 100, 100, 100],
        columns: [
            {data: "e_name"},
            {data: "e_num"},
            {data: "per_shiye"},
            {data: "per_yiliao"},
            {data: "per_yanglao"},
            {data: "per_gongjijin"},
            {data: "com_shiye"},
            {data: "com_yiliao"},
            {data: "com_yanglao"},
            {data: "com_gongshang"},
            {data: "com_shengyu"},
            {data: "com_gongjijin"}
        ],
        stretchH: 'last',
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        minSpareRows: 0,
        contextMenu: true
    });
    var excelHead = '';
    $('.rowCheck').click(function () {
        var salTimeId = $(this).attr('data-id');
        $("#salaryId").val(salTimeId);
        if (salTimeId) {
            $.ajax(
                {
                    type: "post",
                    url: "index.php?action=Salary&mode=getLastDianfuYanfuBySalTimeId",
                    data: {
                        salTimeId : salTimeId
                    },
                    dataType: "json",
                    success: function(data){
                        if(data) {
                            dianfu.loadData(data.dianfu);
                            $('#dianfuNum').text(data.dianfu.length);
                            $('#yanfuNum').text(data.yanfu.length);
                            yanfu.loadData(data.yanfu);
                        }
                    }
                }
            );
        }
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
    $("#import").click(function(){
        $("#excelForm").attr("action","index.php?action=Salary&mode=salaryImport");
        $("#excelForm").submit();
    });
    $(".rowAdd").click(function(){
        var salTimeId = $(this).attr("data-id");
        $("#salTimeId").val(salTimeId);
        $("#iForm").attr("action","index.php?action=Salary&mode=toAddNewSal");
        $("#iForm").submit();

    });
    $(".rowDell").click(function(){
        if (confirm("要删除本月工资吗？")) {
            var salTimeId = $(this).attr("data-id");
            $.ajax({
                url: "index.php?action=Salary&mode=delSalaryAjax",
                data: {
                    salTimeId : salTimeId
                }, //returns all cells' data
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    if (res.code == 100000) {
                        alert(res.message);
                        window.location.reload();
                    }
                    else {
                        alert(res.message);
                    }
                }
            });
        }


    });
});/**
 * Created by zhangchao8189888 on 15-1-3.
 */