/**
 * Created by zhangchao8189888 on 15-5-9.
 */
/**
 * Created by chaozhang204017 on 15-1-15.
 */
$(document).ready(function () {
    $("#searchBtn").click(function() {
        console.log($("#dList").val());
    });
    var data = [
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
        ];
    var salGrid = document.getElementById("emSalGrid");
    var emSal = new Handsontable(salGrid,{
        data: data,
        startRows: 5,
        startCols: 4,
        colHeaders: true,
        colHeaders: ['部门名称','员工名称', '2015-01-01', '2015-02-01', '2015-03-01',
            '2015-04-01', '2015-05-01'
        ],
        colWidths: [100,160, 100, 100, 100, 100, 100, 100, 100, 100, 100],
        //columns: [],
        mergeCells: [
            {row: 0, col: 0, rowspan: 3, colspan: 1},
            {row: 3, col: 0, rowspan: 5, colspan: 1},
            {row: 8, col: 0, rowspan: 5, colspan: 1}
        ],
        stretchH: 'last',
        manualColumnResize: true,
        manualRowResize: true,
        minSpareRows: 0,
        contextMenu: true
    });
});/**
 * Created by zhangchao8189888 on 15-1-3.
 */