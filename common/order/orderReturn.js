/**
 * Created by chaozhang204017 on 14-11-13.
 */
$(function(){
    $("#returnSubmit").click(function (){
        var obj = {
            ids : [],
            price : [],
            proNum : [],
            sumMoney:[],
            customerId:0,
            totalMoney:0.00,
            isOff : 0,
            realTotalMoney:0.00
        };
        obj.ids = [];
        obj.customerId = $("#cId").val();
        obj.remark = $("#remark").val();
        obj.dingDate = $("#dingDate").val();
        obj.payType = $("#payType").val();
        obj.payStatus = $("#payStatus").val();
        obj.custo_discount = $("#custo_discount").val();
        for (var i =0;i< Order.tableList.length;i++) {
            var oData = Order.tableList[i];
            if (oData) {
                obj.ids.push(oData.id);
            }
        }
        var rowId = 0;
        $("table tr").each(function(){

            var tdMoney =$(this).children("td").eq(6);
            var tdPrice =$(this).children("td").eq(5);
            var tdNum =$(this).children("td").eq(3);
            var money = tdMoney.html();
            var price = tdPrice.html();
            var proNum = tdNum.html();
            if (rowId > 0) {
                if (money && money > 0){
                    obj.sumMoney.push(money);
                    obj.price.push(price);
                    obj.proNum.push(proNum);
                }
            }

            rowId ++;
        });
        var totalMoney = Order.galobal.OrderData.getTotalMoney();
        obj.totalMoney = totalMoney;
        if ($("#discounts").attr("checked") == 'checked') {
            obj.isOff = 1;
        }
        obj.realTotalMoney = $(".total-rel-money").html();
        if (!obj.realTotalMoney) {
            obj.realTotalMoney = totalMoney;
        }
        if ($("#discounts").attr("checked") == 'checked' && $("#remark").val() == ''){
            alert('申请特价建议填写备注信息');
            return;
        }
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Order&mode=saveOrderReturnJson",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        return;
                    } else {
                        window.location.href = 'index.php?action=Order&mode=toOrderReturnList';
                        //window.location.reload();
                    }



                }
            }
        );
    });
});