<?php

if (! session_id ()) {
    session_start ();
}

function getFile($dir) {
    $fileArray[]=NULL;
    if (false != ($handle = opendir ( $dir ))) {
        $i=0;
        while ( false !== ($file = readdir ( $handle )) ) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != ".."&&strpos($file,".")) {

                $fileArray[$i]="./imageroot/current/".$file;

                if($i==100){
                    break;
                }
                $i++;
            }
        }
        //关闭句柄
        closedir ( $handle );
    }
    return $fileArray;
}
$url="";
if(!empty($_REQUEST['url'])){
    $url=$_REQUEST['url'];
}else{
    $urls=getFile("imageroot/current");
    if(count($urls)==1){
        echo "<script>alert('所有图片修改完成');</script>";
        echo "<script>window.close;</script>";
    }else if(count($urls)==2){
        $url=$urls[0];
    }else{
        $url=$urls[rand(1,count($urls))-1];
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <title>截图工具</title>
    <link rel="stylesheet" type="text/css" href="common/css/uploadify.css"/>
    <link rel="stylesheet" href="common/css/jquery.Jcrop.css" type="text/css" />
    <link rel="stylesheet" href="common/css/imgtool.css" type="text/css" />
    <script language="javascript" type="text/javascript" src="common/js/jquery.min.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="common/js/bootstrap.min.js" charset="utf-8"></script>
    <!--<script type="text/javascript" src="common/js/jquery.uploadify-3.1.js"></script>-->
    <script src="common/js/jquery.uploadify-3.1.js?ver=<?php echo rand(0,9999);?>" type="text/javascript"></script>
    <script src="common/js/imgUpload/jquery.Jcrop.js" type="text/javascript"></script>
    <script src="common/js/imgUpload/upload.js" type="text/javascript"></script>
</head>
<style type="text/css">
    .uploadify-button {
        position: relative;
        overflow: hidden;
        margin-bottom: 10px;
        color: #fff;
        background-color: #51a315;
        font-size: 14px;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
    }
    .uploadify:hover .uploadify-button {
        position: relative;
        overflow: hidden;
        margin-bottom: 10px;
        color: #fff;
        background-color: #51a315;
    }
</style>
<script language="javascript" type="text/javascript">
    $(function(){
        var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
        var i=0;//初始化数组下标
        $('#file_upload').uploadify({
            minSize: [60,60],
            setSelect: [0,0,190,190],
            'auto'     : true,//关闭自动上传
            'removeTimeout' : 1,//文件队列上传完成1秒后删除
            'swf'      : 'common/js/uploadify.swf',
            'uploader' : 'index.php?action=Product&mode=fileUpload',
            'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText' : '',//设置按钮文本
            'multi'    : true,//允许同时上传多张图片
            'uploadLimit' : 1,//一次最多只允许上传10张图片
            'fileTypeDesc' : 'Image Files',//只允许上传图像
            'fileTypeExts' : '*.gif; *.jpg; *.png',//限制允许上传的图片后缀
            'fileSizeLimit' : '20000KB',//限制上传的图片不得超过200KB
            width           : 90,
            height           : 32,
            buttonText:"上传图片",
            'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
                img_id_upload[i]=data;
                i++;
                //alert(data);
                data = jQuery.parseJSON(data);
                if (data.code == '100000') {
                    $('#target').attr('src',data.imageUrl);
                    $('#img60').attr('src',data.imageUrl);
                    $('#img200').attr('src',data.imageUrl);
                    var ImgD = {};
                    ImgD.src = $('#target').attr('src');
                    ImgD.height = data.height;
                    ImgD.width = data.width;
                    $('#target').load(DrawImage(ImgD,500,450));
                    $('#target').Jcrop({
                        onChange:   updatePreview,
                        onSelect:   updatePreview,
                        onRelease:  clearCoords,
                        aspectRatio: 1
                    },function(){
                        // Use the API to get the real image size
                        var bounds = this.getBounds();
                        boundx = bounds[0];
                        boundy = bounds[1];
                        // Store the API in the jcrop_api variable
                        jcrop_api = this;
                    });
                }
            },
            'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
                // if(img_id_upload.length>0)
                // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
            }
            // Put your options here
        });
        var jcrop_api, boundx, boundy,CutJson60 = {},CutJson200  = {};
        CutJson60.position = {};
        CutJson200.position = {};

        function updatePreview(c){
            if (parseInt(c.w) > 0){
                $('#img200').css({
                    width: Math.round(200 / c.w * boundx) + 'px', //200 为预览div的宽和高</span>
                    height: Math.round(200 / c.h * boundy) + 'px',
                    marginLeft: '-' + Math.round(200 / c.w * c.x) + 'px',
                    marginTop: '-' + Math.round(200 / c.h * c.y) + 'px'
                });
                $('#img60').css({
                    width: Math.round(60 / c.w * boundx) + 'px', //200 为预览div的宽和高</span>
                    height: Math.round(60 / c.h * boundy) + 'px',
                    marginLeft: '-' + Math.round(60 / c.w * c.x) + 'px',
                    marginTop: '-' + Math.round(60 / c.h * c.y) + 'px'
                });
                $('#w').val(c.w);  //c.w 裁剪区域的宽
                $('#h').val(c.h); //c.h 裁剪区域的高
                $('#x1').val(c.x);  //c.x 裁剪区域左上角顶点相对于图片左上角顶点的x坐标
                $('#y1').val(c.y);  //c.y 裁剪区域顶点的y坐标</span>
                CutJson60.position.x1 = Math.round(60 / c.w * c.x);
                CutJson60.position.y1 = Math.round(60 / c.h * c.y);
                CutJson60.position.width  = c.x;
                CutJson60.position.height = c.y;
                CutJson200.position.x1 = Math.round(200 / c.w * c.x);
                CutJson200.position.y1 = Math.round(200 / c.h * c.y);
                CutJson200.position.width  = Math.round(200 / c.w * boundx);
                CutJson200.position.height = Math.round(200 / c.h * boundy);
            }
        };

        function clearCoords()
        {
            $('#coords input').val('');
            $('#h').css({color:'red'});
            window.setTimeout(function(){
                $('#h').css({color:'inherit'});
            },500);
        };
        $(".savePic").click(function(){
            /*CutJson60.position.x1 = $('#x1').val();
            CutJson60.position.y1 = $('#y1').val();
            CutJson60.position.width  =  $('#w').val();
            CutJson60.position.height = $('#h').val();
            $.ajax({
                type: "POST",
                url : "index.php?action=Product&mode=picCut",
                data: {
                    name:$('#target').attr('src'),
                    scale:$('#scale').val(),
                    position:JSON.stringify(CutJson60.position) },
                success: function(data){
                    $('#suolv',parent.document).attr('src',data); //裁剪成功传回生成的新图文件名，将结果图显示到页面
                    $('#modal-event1',parent.document).modal('toggle');
                    //$('#modal-event1',parent.document).modal('hide');
                    event.fire($('#modal-event1',parent.document),function () {
                        this.modal('hide');
                    });
                }
            });*/
            $('#modal-event1',parent.document).modal('toggle');
            $('#modal-event1',parent.document).modal('hide');
        });
    });

</script>
<body>
<div id="outer">
    <div class="jcExample">
        <div class="article viewerM">
                <img src="common/img/product480x480.png" alt="target" id="target" style="width: 500px;height: 450px" /><!--onload="javascript:DrawImage(this,500,450);"-->

            <form id="coords"  class="coords" action="imgresize.php" method="get" onsubmit="return check();">
                <div>
                    <!-- 起始位置的x坐标 -->
                    <label>X<input type="text" size="10" id="x1" name="x" readonly/></label>
                    <!-- 起始位置的y坐标 -->
                    <label>Y<input type="text" size="10" id="y1" name="y" readonly/></label>
                    <!-- 宽 -->
                    <label>宽<input type="text" size="10" id="w" name="w" readonly/></label>
                    <!-- 高 -->
                    <label>高<input type="text" size="10" id="h" name="h" readonly/></label>
                    <input type="hidden" size="10" id="src" name="src" value="<?=$url ?>" />
                    <br />
                    <label>源图片/当前图片=<input type="text" size="10" id="scale" name="scale" readonly/></label>
                    <label>继续在此张图片截图<input name="again" type="checkbox" /></label>
                    <label>
                        <input type="button"  value="保存" class="savePic" style="height:50px; width:200px; font-size:24px; background-color:green;border:green thin solid;"/>
                    </label>
                </div>
            </form>
        </div>
        <div class="viewerR">
            <span class="ui-btn ui-btn-green fileinput-button"><input type="file" name="files[]" id="file_upload" autocomplete="off"></span>
            <p class="lite-gray lh30 pb10">上传的图片将自动生成三种尺寸，请注意生成图片是否清晰</p>
            <div class="cb"></div>
            <div class="preContainer200">
                <div class="px200">
                    <img class="img-target" id="img200" src="common/img/product200x200.png"  width="200px" height="200px">
                </div>
                <p>中等尺寸</p>
                <p>200*200</p>
            </div>
            <div class="preContainer60">
                <div class="px60">
                    <img class="img-target" id="img60" src="common/img/product60x60.png">
                </div>
                <p>小尺寸</p>
                <p>60*60</p>
            </div>
            <!--<div class="cb"></div>
            <p>大尺寸480*480像素<a href="javascript:void(0)" class="theme-color prev480">预览</a></p>-->
        </div>



    </div>
</div>
</body>

</html>
