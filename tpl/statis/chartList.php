<?php
$department=$form_data['department'];
?>
<script type="text/javascript">
    $(function () {
        /*$('#container').highcharts({
            credits:{
                text:''
            },
            exporting: {
                enabled: false
            },
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '各部门工资统计'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '装修公司',
                colors: ['#50B432', '#FF8000', '#C0C0C0'],
                data: [
                    ['已通过',   <?php echo $pass?>],
                    ['未审核',   <?php echo $audit?>],
                    ['被拒绝',   <?php echo $reject?>],
                ]
            }]
        });*/
    });

    $(function () {
        $('#container1').highcharts({
            exporting: {
                enabled: false
            },
            chart: {
                type: 'column'
            },
            credits:{
                text:''
            },
            title: {
                text: '部门工资统计'
            },
            xAxis: {
                categories: <?php echo json_encode($department)?>
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total fruit consumption'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -70,
                verticalAlign: 'top',
                y: 20,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [  {
                name: '应发合计',
                color: '#C0C0C0',
                data: [88.4, 21.8, 20.1, 20,50,60,70,80,90,100]
            },{
                name: '代扣税',
                color: '#FF8000',
                data: [88.4, 21.8, 20.1, 20,50,60,70,80,90,100]
            },{
                name: '补扣税',
                color: '#50B432',
                data: [88.4, 21.8, 20.1, 20,50,60,70,80,90,100]
            }]
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">统计管理</a>
            <a href="#">工资统计</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="widget-box">
                <div class="widget-title bg_lg"><span class="icon"><i class="icon-bar-chart"></i></span>
                    <h5>部门工资统计</h5>
                </div>
                <div class="widget-content" >
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="pie" id="container1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="row-fluid">
            <div class="widget-box">
                <div class="widget-title bg_lg"><span class="icon"><i class="icon-user"></i></span>
                    <h5>装修公司</h5>
                </div>
                <div class="widget-content" >
                    <div class="row-fluid">
                        <div class="span9">
                            <div class="pie" id="container"></div>
                        </div>
                        <div class="span3">
                            <ul class="site-stats">
                                <li class="bg_ls"><i class="icon-globe"></i> <strong><?php /*echo $all*/?></strong> <small>全部</small></li>
                                <li class="bg_lg"><i class="icon-ok"></i> <strong><?php /*echo $pass*/?></strong> <small>已通过</small></li>
                                <li class="bg_ly"><i class="icon-pencil"></i> <strong><?php /*echo $audit*/?></strong> <small>未审核</small></li>
                                <li class="bg_lh"><i class="icon-minus-sign"></i> <strong><?php /*echo $reject*/?></strong> <small>被拒绝</small></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</div>
</div>
<script type="text/javascript" src="common/js/highchart/highcharts.js"></script>
<script type="text/javascript" src="common/js/highchart/themes/sand-signika.js"></script>
