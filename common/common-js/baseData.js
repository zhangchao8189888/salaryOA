$(function(){
    $("#company_validate").validate({
        onsubmit:true,
        submitHandler:function(form){
            var obj = {};
            obj.shenfenType = $("#shenfenType").val();
            obj.type_id = $("#type_id").val();
            obj.id = $("#typeId").val();
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=BaseData&mode=saveOrUpdateShenfenType",
                    data: obj,
                    dataType:'json',
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.mess);
                            return;
                        }
                        window.location.reload();
                    }
                }
            );
        },
        rules: {
            shenfenType: { required: true }
        },
        messages: {
            shenfenType:
            {
                required: '必填'
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
});/**
 * Created by chaozhang204017 on 15-1-22.
 */
