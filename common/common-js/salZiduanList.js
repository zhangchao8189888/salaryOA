/**
 * Created by zhangchao8189888 on 15-4-9.
 */
$(document).ready(function(){
    $("#addZd").click(function(){
        var data = {
            zd_name : $('#ziduanName').val(),
            zd_type : $('#ziduanType').val()
        };
        $.ajax({
            type:'POST',
            url:'index.php?action=BaseData&mode=addZiduan',
            data: data,
            dataType:'Json',
            success:function(result){
                if(result.code == 100000){
                    window.location.reload();
                }else{
                    alert(result.mess);
                }
            }
        })
    });
    $(".rowDelete").click(function () {
        var id = $(this).attr('data-id');
        var data = {
            zid : id
        };
        $.ajax({
            type:'POST',
            url:'index.php?action=BaseData&mode=delZiduan',
            data: data,
            dataType:'Json',
            success:function(result){
                if(result.code == 100000){
                    window.location.reload();
                }else{
                    alert(result.mess);
                }
            }
        })
    });

});