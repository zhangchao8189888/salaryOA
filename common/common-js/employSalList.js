/**
 * Created by zhangchao8189888 on 15-5-9.
 */
/**
 * Created by chaozhang204017 on 15-1-15.
 */
$(document).ready(function () {
    $("#searchBtn").click(function() {
        if($("#startDate").val()=='' ||$("#endDate").val()=='' ){
            return;
        }
        var aa =[];
        $("input[name='departId']:checkbox:checked").each(function(){
            aa.push($(this).val());
        })
        $.ajax(
            {
                type: "post",
                url: "index.php?action=Statis&mode=getEmpSalList",
                dataType: "json",
                data: {
                    departId : aa,
                    startDate : $("#startDate").val(),
                    endDate : $("#endDate").val()

                },
                success: function(data){
                    var jData = data.rowData;
                    var head = data.colHeaders;
                    var headWith = data.colWidths;
                    var mergeCells = data.mergeCells;
/*
                    emSal.updateSettings({
                        colHeaders: head
                    });


                    emSal.updateSettings({
                        mergeCells:[
                            {row: 0, col: 0, rowspan: 3, colspan: 1},
                            {row: 3, col: 0, rowspan: 5, colspan: 1},
                            {row: 8, col: 0, rowspan: 5, colspan: 1}
                        ]
                    });
                    var sumWith = 100;

                    for (var i =0;i < headWith.length;i++) {
                        sumWith+= headWith[i];
                    }
                    if(sumWith >1400) {
                        sumWith = 1400;
                    }
                    $('#emSalGrid').css('width',sumWith);
                    emSal.updateSettings({
                        colWidths: headWith
                    });
                    emSal.loadData(jData);*/
                    $("#emSalGrid").html('');
                    var salGrid = document.getElementById("emSalGrid");
                    var emSal = new Handsontable(salGrid,{
                        data: jData,
                        startRows: 5,
                        startCols: 4,
                        colHeaders: true,
                        colHeaders: head,
                        colWidths: headWith,
                        //columns: [],
                        mergeCells: mergeCells,
                        stretchH: 'last',
                        manualColumnResize: true,
                        manualRowResize: true,
                        minSpareRows: 0,
                        contextMenu: true
                    });

                }
            }
        );
    });
    /*var data = [
            ['部办', '甲', '1000', '1000', '1000', '1000', '1000'],
            ['部办1', '乙', '1000', '1000', '1000', '1000', '1000'],
            ['部办2', '丙', '1000', '1000', '1000', '1000', '1000'],
            ['产品保证处', '丁', '1000', '1000', '1000', '1000', '1000'],
            ['产品保证处', '戊己', '1000', '1000', '1000', '1000', '1000'],
            ['产品保证处', '己', '1000', '1000', '1000', '1000', '1000'],
            ['产品保证处', '庚', '1000', '1000', '1000', '1000', '1000'],
            ['产品保证处', '辛', '1000', '1000', '1000', '1000', '1000'],
            ['保密处', '辛', '1000', '1000', '1000', '1000', '1000'],
            ['保密处', '辛', '1000', '1000', '1000', '1000', '1000'],
            ['保密处', '辛', '1000', '1000', '1000', '1000', '1000'],
            ['保密处', '辛', '1000', '1000', '1000', '1000', '1000'],
            ['保密处', '辛', '1000', '1000', '1000', '1000', '1000']
        ];*/

});/**
 * Created by zhangchao8189888 on 15-1-3.
 */