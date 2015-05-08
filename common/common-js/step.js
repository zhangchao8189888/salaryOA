/**
 * Created by zhangchao8189888 on 15-5-2.
 */
$(function(){
    $(".step ul li .mark").click(
            function(){
                window.location.href = $(this).attr("src");
            }

    );
})