<!DOCTYPE html>
<html>
<head>
    <title>图片上传前预览剪裁-柯乐义</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <script type="text/javascript" src="http://keleyi.com/keleyi/pmedia/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="http://keleyi.com/keleyi/phtml/image/19/jquery.Jcrop.min.js"></script>
    <link href="http://keleyi.com/keleyi/phtml/image/19/jquery.Jcrop.min.css" rel="stylesheet">
    <style>
        .upload-btn
        {
            width: 130px;
            height: 25px;
            overflow: hidden;
            position: relative;
            border: 3px solid #06c;
            border-radius: 5px;
            background: #0cf;
        }
        .upload-btn:hover
        {
            background: #09f;
        }
        .upload-btn__txt
        {
            z-index: 1;
            position: relative;
            color: #fff;
            font-size: 18px;
            font-family: "Helvetica Neue";
            line-height: 24px;
            text-align: center;
            text-shadow: 0 1px 1px #000;
        }
        .upload-btn input
        {
            top: -10px;
            right: -40px;
            z-index: 2;
            position: absolute;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
            font-size: 50px;
        }
    </style>
</head>
<body>
<div>
    <!-- "js-fileapi-wrapper" -- required class -->
    <div class="js-fileapi-wrapper upload-btn" id="choose">
        <input name="files" type="file" multiple />
        <button id="btn">
            选择图片</button>
    </div>
    <div id="images">
        <p style="margin-top: 10px;">
        </p>
        <div id="img2" style="border: 1px solid silver; width: 500px; float: left;">
        </div>
        <div id="img3" style="border: 1px solid green; width: 500px; float: left; margin-left: 5px;">
        </div>
    </div>
</div>
<div style="clear:both"><a href="http://keleyi.com/a/bjad/liuvpkke.htm" target="_blank">原文</a></div>
<script type="text/javascript"> window.FileAPI = { staticPath: './fileapi/' };</script>
<script type="text/javascript" src="http://keleyi.com/keleyi/phtml/image/19/FileAPI.min.js"></script>
<script type="text/javascript">
    var el = $('input').get(0);

    FileAPI.event.on(el, 'change', function (evt) {
        var files = FileAPI.getFiles(evt); // Retrieve file list

        FileAPI.filterFiles(files, function (file, info) {
            if (!/^image/.test(file.type)) {
                alert('图片格式不正确');
                return false;
            }
            else if (file.size > 20 * FileAPI.MB) {
                alert('图片必须小于20M');
                return false;
            }
            else {
                return true;
            }

        }, function (files, rejected) {


            if (files.length) {
                var file = files[0];
                var img0 = FileAPI.Image(file);
                var img1 = FileAPI.Image(file);
                var ratio = 0;
                FileAPI.getInfo(file, function (err, info) { //get image ratio
                    if (!err) {
                        if (info.width > info.height) {
                            ratio = info.width / 500;
                        }
                        else {
                            ratio = info.height / 500;
                        }
                    }
                });

                img0.resize(500, 500, 'max') //place image and register jcrop
                    .get(function (err, img) {
                        $('#img2').empty();
                        $('#img2').append($(img));

                        $('#img2').children().Jcrop({
                            aspectRatio: 1,
                            bgColor: 'rgba(0,0,0,0.4)',
                            onSelect: function (c) {
                                img1.matrix.sx = c.x * ratio;
                                img1.matrix.sy = c.y * ratio;
                                img1.matrix.sw = c.w * ratio;
                                img1.matrix.sh = c.h * ratio;
                                img1.matrix.dw = 500;
                                img1.matrix.dh = 500;

                                img1.get(function (err, img) {
// $('#img3').empty();
// $('#img3').append($(img));
                                    $('#img3').html($(img));
                                });

                            }
                        });
                    });
                $('#btn').on('click', function () {
                    FileAPI.upload({
                        url: '/testUpFile/upFile',

                        files: { images: img1 },
                        progress: function (evt) { /* ... */ },
                        complete: function (err, xhr) { /* ... */
//alert(xhr.responseText);

                        }
                    });

                });


            }
        });
    });
</script>
</body>
</html>