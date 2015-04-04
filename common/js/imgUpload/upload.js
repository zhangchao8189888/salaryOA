$(function(){
    var jcrop_api, boundx, boundy;

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

});


//等比例缩小图片,并且计算出比例，否在在服务器端截图时候会错位
function DrawImage(ImgD,FitWidth,FitHeight){
    var image=new Image();
    image.src=ImgD.src;
    image.height = ImgD.height;
    image.width = ImgD.width;
    if(image.width>0 && image.height>0){
        if(image.width/image.height>= FitWidth/FitHeight){
            if(image.width>FitWidth){
                ImgD.width=FitWidth;
                ImgD.height=(image.height*FitWidth)/image.width;
            }else{
                ImgD.width=image.width;
                ImgD.height=image.height;
            }
            $("#scale").val(image.width/ImgD.width);
        } else{
            if(image.height>FitHeight){
                ImgD.height=FitHeight;
                ImgD.width=(image.width*FitHeight)/image.height;
            }else{
                ImgD.width=image.width;
                ImgD.height=image.height;
            }
            $("#scale").val(image.width/ImgD.width);
        }
    }
}
function check(){
    if($("#x1").val()==""||$("#src").val()==""){
        alert("请选择区域");
        return false;
    }
    return true;
}