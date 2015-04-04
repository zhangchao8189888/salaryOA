<?php
$levelList=$form_data['levelList'];
$jingbanList=$form_data['jingbanList'];
?>
<style>
    .control-group .control-input {
        float: left;
        line-height: 32px;
        padding-left: 10px;
    }
    .control-group-line .control-label-2 {
        width: 50px;
    }
    .album li {
        float: left;
        margin: 5px 20px 15px 0;
        width: 142px;
        height: 140px;
    }

    .album ul {
        height: 175px;
        float: left;
        #overflow: hidden;
    }
    li, ul {
        list-style: outside none none;
    }

    .album li img {
        border: 1px solid #e1e1e1;
        height: 140px;
    }
    .table-wrap-lite td {
        text-align: center;
    }
    .table-wrap-lite tbody td {
        background-color: #fff;
        border: 1px solid #e5e8ea;
        color: #666;
        height: 44px;
        padding: 0 5px;
        table-layout: fixed;
        vertical-align: middle;
        word-break: break-all;
    }
    .product-img-thumb {
        border: 1px solid #e1e1e1;
        height: 60px;
        margin: 5px auto;
        overflow: hidden;
        position: relative;
        width: 60px;
        z-index: 0;
    }
    .product-img-thumb img {
        border: 0 none;
        height: 60px;
        width: 60px;
        z-index: 1;
    }
    .ui-btn-edit-white-ico {
        background-position: -20px -20px;
        height: 15px;
        width: 15px;
    }
    .product-img-thumb .op {
        bottom: 0;
        display: none;
        height: 25px;
        position: absolute;
        width: 100%;
        z-index: 10;
    }
    .product-img-thumb .op-bg {
        background-color: #000;
        bottom: 0;
        height: 25px;
        opacity: 0.5;
    }
    .product-img-thumb .op-btn {
        bottom: 0;
        height: 25px;
        position: absolute;
        width: 100%;
    }

    .product-img-thumb .op-btn a {
        color: #fff;
        display: block;
        line-height: 25px;
    }
    .modal {
        background-clip: padding-box;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.3);
        border-radius: 6px;
        box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
        left: 50%;
        margin-left: -540px;
        outline: 0 none;
        position: fixed;
        top: 10%;
        width: 1100px;
        z-index: 1050;
        height: 700px;
    }
</style>
<link rel="stylesheet" type="text/css" href="common/css/uploadify.css"/>
<script src="common/js/jquery.uploadify-3.1.js?ver=<?php echo rand(0,9999);?>" type="text/javascript"></script>
<script type="text/javascript">
    var event = {};
    event.fire = function (obj,func) {
        if (obj) {
            func && func.call(obj);
        }
    }
    $(function(){


        $(".op").show();
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
        var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
        var i=0;//初始化数组下标
        $('#fileUpload').uploadify({
            'auto'     : true,//关闭自动上传
            'removeTimeout' : 1,//文件队列上传完成1秒后删除
            'swf'      : 'common/js/uploadify.swf',
            'uploader' : 'index.php?action=Product&mode=fileUpload',
            'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText' : '',//设置按钮文本
            'multi'    : true,//允许同时上传多张图片
            'uploadLimit' : 4,//一次最多只允许上传10张图片
            'fileTypeDesc' : 'Image Files',//只允许上传图像
            'fileTypeExts' : '*.gif; *.jpg; *.png',//限制允许上传的图片后缀
            'fileSizeLimit' : '20000KB',//限制上传的图片不得超过200KB
            width           : 140,
            height           : 140,
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
                img_id_upload[i]=data;
                i++;
                //alert(data);
                data = jQuery.parseJSON(data);
                if (data.code == '100000') {
                    $("#album-container").append(' <li>' +
                        '<a target="_blank" href="'+data.imageUrl+'" class="srcFileUrl">' +
                        '<img width="140" src="'+data.imageUrl+'"></a>' +
                        '<div>' +
                        '<input type="hidden" value="data.imageUrl" class="albumSrcFileName">' +
                        '<div class="progress-op"><a class="theme-color pic-delete" href="javascript:void(0)">删除</a></div>' +
                        '</div>' +
                        '</li>');
                }
            },
            'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
                // if(img_id_upload.length>0)
                // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
            }
            // Put your options here
        });
        $(".product-img-thumb").hover(function () {
            $(".op").show();
        },function(){
            $(".op").hide();
        });
        //$("#target").onload(DrawImage(this,500,450));
        $(".edit-pic").click(function(){
            $('#modal-event1').modal({show:true});
            $("#productImgUpload").attr("src","pic_upload.php?url=picUpload/Tulips.jpg");
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Product&mode=getProductList">商品</a>
            <a href="#" class="current">新增商品</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span6">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>基础资料</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form action="index.php?action=Product&mode=addProduct" id="customer_validate" method="post" class="form-horizontal" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>商品型号:</label>
                                <div class="controls">
                                    <input type="text" name="product_code" id="product_code" class="span11" placeholder="商品型号">
                                </div>
                            </div>
                            <div class="control-group product-list-group">
                                <div class="control-label"></div>
                                <div class="control-input">
                                    <div class="table-wrap-lite product-list">
                                        <table>

                                            <thead>
                                            <tr>

                                                <th width="50" style="min-width:50px" class="pImg">商品略图</th>
                                                <th style="min-width:130px;" class="pBarCode">条形码</th>
                                                <th width="40" rowspan="2">操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr data="1" class="1">
                                                <td class="pImg">
                                                    <div class="product-img-thumb">
                                                        <img id="suolv" alt="" src="common/img/product80x80.png">
                                                        <input type="hidden" value="" class="srcFileName">
                                                        <div class="op" style="display: none;">
                                                            <div class="op-bg"></div>
                                                            <div class="op-btn"><a href="#" class="edit-pic"><em class="icon-edit"></em>编辑</a></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" value="" name="barCode_P40421025069" class="ui-input barcode">
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="delete">删除</a>
                                                    <a href="javascript:void(0)" class="restore hidden">恢复</a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">供应商:</label>
                                <div class="controls">
                                    <input type="text" name="product_supplier" id="product_supplier" class="span11">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>货品简称:</label>
                                <div class="controls">
                                    <input type="text" name="product_name" id="product_name" class="span11" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">货品类别:</label>
                                <div class="controls">
                                    <input type="text" name="product_type" id="product_type" class="span11" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">单位:</label>
                                <div class="controls">
                                    <input type="text" name="product_unit" id="product_unit" class="span5" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">数量:</label>
                                <div class="controls">
                                    <input type="text" name="product_num" id="product_num" class="span3" value="0" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">有无折扣:</label>
                                <div class="controls">
                                    <select name="flag" id="flag">
                                        <option value="0" <?php if($productPo['pro_flag']==0)echo 'selected'?>>无折扣</option>
                                        <option value="1" <?php if($productPo['pro_flag']==1)echo 'selected'?> >有折扣</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">市场价格:</label>
                                <div class="controls">
                                    <input type="text" name="product_price" id="product_price" class="span5" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">商品图册：</label>
                                <div class="control-input album"><div class="progress-container">
                                        <div style="width:0;" class="progress-bar"></div>
                                    </div>
                                    <ul id="album-container">
                                    </ul>
                                    <ul>
                                        <li class="">
                                            <input type="file" multiple="" name="files" id="fileUpload" class="valid pic">
                                            <input type="hidden" value="1215154" name="tmpdir" id="id_file">


                                        </li>
                                    </ul>
                                    <div class="cb"></div></div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success">保存</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal  hide" id="modal-event1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>添加客户</h3>
    </div>
    <div class="modal-body" style="max-height: 600px;height: 600px">
        <iframe frameborder="0" id="productImgUpload" style="width: 100%; height: 100%; border: 0px none;" src="">

        </iframe>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="cust_add">添加</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function() {
        $("#customer_validate").validate({
            rules: {
                product_code :  {
                    required: true,
                    remote:{                                          //验证用户名是否存在
                        type:"POST",
                        url:"index.php?action=Product&mode=verifyProductCode",             //servlet
                        data:{
                            code:function(){return $("#product_code").val();}
                        }
                    }
                },
                product_num: {
                    required: true,
                    number:true
                },
                product_price : {
                    required: true,
                    number:true
                }

            },
            messages: {
                product_num:
                {
                    required: '必填'
                },
                product_code:
                {
                    required: '（唯一标识客户，不能重复，必填。）',
                    remote : '产品编码已经存在'
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
    function pack(dp){
        if(!confirm('日期框原来的值为: '+dp.cal.getDateStr()+', 要用新选择的值:' + dp.cal.getNewDateStr() + '覆盖吗?'))
            return true;
    }
    function changeDate(dp) {

        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Customer&mode=sumNongli",
                async:false,
                data: {
                    date: dp.cal.getNewDateStr()
                },
                dataType: "json",
                success: function(data){
                    if (data.data){
                        $("#nongli").val(data.data);
                    }

                }
            }
        );
    }
</script>