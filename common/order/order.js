/**
 * Created by chaozhang204017 on 14-10-30.
 */
Array.prototype.remove=function(obj){
    for(var i =0;i <this.length;i++){
        var temp = this[i];
        if(!isNaN(obj)){
            temp=i;
        }
        if(temp == obj){
            for(var j = i;j <this.length;j++){
                this[j]=this[j+1];
            }
            this.length = this.length-1;
        }
    }
}
var util = {
    extend : function(oTarget, oSource, fOverwrite) {
        if (!oTarget) {
            oTarget = {};
        }

        if (!oSource) {
            return oTarget;
        }

        for (var k in oSource) {
            v = oSource[k];

            if (util.isDef(v) && (fOverwrite || !util.isDef(oTarget[k]))) {
                oTarget[k] = v;
            }
        }

        return oTarget;
    },
    isDef : function(o) {
        return typeof o != 'undefined';
    },
    isNum : function(o) {
        return typeof o == 'number' && o != null;
    },
    isArray : function(o) {
        return o && (typeof(o) == 'object') && (o instanceof Array);
    },
    isStr : function(o) {
        return o && (typeof o == 'string' || o.substring);
    },
    isWinActive : function() {
        return util.STORE.__bWinActive;
    },
    wait : function(fnCond, fnCb, nTime) {
        function waitFn() {
            if (fnCond()) {
                fnCb();
            } else {
                W.setTimeout(waitFn, util.isNum(nTime) ? nTime : 100);
            }
        };

        waitFn();
    },
    delay : function(iTime) {
        var t, arg;

        if ($.isFunction(iTime)) {
            arg = [].slice.call(arguments, 0);
            t = 10;
        } else {
            arg = [].slice.call(arguments, 1);
            t = iTime;
        }

        if (arg.length > 0) {
            var fn = arg[0], obj = arg.length > 1 ? arg[1] : null, inputArg = arg.length > 2 ? [].slice.call(arg, 2) : [];

            return W.setTimeout(function() {
                fn.apply(obj || W, inputArg);
            }, t);
        }
    },
    clearDelay : function(n) {
        W.clearTimeout(n);
    }
};
var Order ={};
Order.orderList = [];
Order.tableList = [];
Order.galobal= {};
var cOrderData = function () {
    var totalMoney;
    this.getTotalMoney = function() {
        return totalMoney;
    }
    //setter
    this.setTotalMoney = function(t)
    {
        totalMoney = t;
    }

};
Order.galobal.OrderData =  new cOrderData();
var Customer = {};
Customer.oCustomer = {
    fnGetCustomerInfo : function (obj) {
        var cusObj = obj;
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Customer&mode=getCustomerByIdJson",
                async:false,
                data: cusObj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        return;
                    }
                    var custoData = data.data;
                    Customer.oCustomer.info = custoData;
                    $("#customer_add").val(obj.name);
                    $("#cId").val(obj.id);
                    $("#custo_discount").val(custoData.custo_discount);
                    if ($("#isReturn").val()) {
                        $(".refund-info").show();
                        $(".contact").html(custoData.custoHaed_name);
                        $(".mobile").html(custoData.moveTel_no);
                    }
                }
            }
        );
    }
};
Order.Table={
    fnSort : function() {
        var rowId = 0;
        $("table tr").each(function(){
            var tdIndex =$(this).children("td").eq(0);
            if (rowId > 0){
                tdIndex.html(rowId);
            }
            rowId ++;
        });
    },
    fnSumOrderTotalMoney : function () {
        var rowId = 0;
        var totalMoney = 0.00;
        $("table tr").each(function(){

            var tdMoney =$(this).children("td").eq(6);
            var money = tdMoney.html();
            if (rowId > 0) {
                if (money && money > 0){
                    totalMoney += parseFloat(money);
                }
            }

            rowId ++;
        });
        totalMoney = (totalMoney).toFixed(2);
        Order.galobal.OrderData.setTotalMoney(totalMoney);
        $(".total-money").html(totalMoney);
        if($("#discounts").attr("checked") != 'checked') {
            $(".total-rel-money").html(totalMoney);
        }

    },
    fnAddNewTr :function () {
        var rowId = $("table tr").length -1;
        var oData = Order.tableList[rowId];
        var tr = $("table tr:last");
        if (oData) {
            tr.after('<tr id="'+Order.Table.row+'"><td style="text-align: center;"></td>' +
                '<td style="text-align: center;">' +
                '<a style="cursor: pointer;" class="icon-plus" title="新增行"></a>&nbsp' +
                '<a class="icon-minus" style="cursor: pointer;" title="删除行"></a>' +
                '</td>' +
                '<td class="product_add" style="width: 300px;height:20px;"></td>' +
                '<td class="product_num" style="text-align:center;"></td>' +
                '<td style="text-align:center;"></td>' +
                '<td style="text-align:right;"></td>' +
                '<td style="text-align:right;"></td>' +
                '<td></td></tr>');
            Order.Table.row++;
        }
        this.fnSort();
    },
    fnAddOrder : function (obj,oInput,rowId) {
        if (!Customer.oCustomer.info) {
            alert('请先选择客户！');
            return;
        }
        var nVal = this.fnOrderCombine(obj.id,1);
        if (nVal > 0){//is combine
            this._fnClearTableTr(rowId);
            this.fnSumOrderTotalMoney();
            return;
        }
        oInput.val(obj.name);
        var tr = oInput.parent().parent();
        var nRowIndex = tr.attr('id');
        var tdNum = tr.find('td').eq(3);
        var tdUnit = tr.find('td').eq(4);
        var tdPrice = tr.find('td').eq(5);
        var tdSum = tr.find('td').eq(6);
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Product&mode=getProductByIdJson",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code > 100000) {
                        alert(data.message);
                        return;
                    }
                    var proData = data.data;
                    tdUnit.html(proData.pro_unit);

                    if (Customer.oCustomer.info.custo_discount && Customer.oCustomer.info.custo_discount > 0) {
                        proData.pro_price = proData.pro_price * (Customer.oCustomer.info.custo_discount/100)
                    }
                    tdPrice.html(proData.pro_price);
                    tdSum.html(proData.pro_price);
                    proData.inputText = obj.name;
                    proData.pro_id = proData.id;
                    Order.tableList[nRowIndex] = proData;


                }
            }
        );
        tdNum.html(1);//添加数量
        this.fnSumOrderTotalMoney();
        this.fnAddNewTr();
    },
    fnOrderCombine : function (pid,pNum) {
        var rowIndex = this.fnFindSameOrder(pid);
        if (rowIndex && rowIndex > 0) {
            var oData = Order.tableList[rowIndex];
            var oTr = $("#"+rowIndex);
            var oTdNum = oTr.find('td').eq(3);
            var fPrice = oData.pro_price;
            var oTdSum = oTr.find('td').eq(6);
            var fSumNum = parseFloat(pNum) + parseFloat(oTdNum.html());
            var fSumJin = fSumNum * parseFloat(fPrice);
            oTdSum.html(fSumJin);
            oTdNum.html(fSumNum);

            alert("列表中已经存在该商品，已经合并");

            return 1;
        } else {
            return 0;
        }
    },
    fnFindSameOrder : function (pid) {
        for(var i=0; i< Order.tableList.length; i++) {
            var oOrderData = Order.tableList[i];
            if (oOrderData && pid == oOrderData.id) {
                return i;
            }
        }
        return 0;
    },
    fnModifyForNum : function (rid,fNum) {
        if (!Customer.oCustomer.info) {
            alert('请先选择客户！');
            return;
        }
        var oData = Order.tableList[rid];
        var oTr = $("#"+rid);//tr
        var oTdNum = oTr.find('td').eq(3);//td
        if (Order.tableList[rid]) {

            var fPrice = oData.pro_price;
            if (Customer.oCustomer.info.custo_discount && Customer.oCustomer.info.custo_discount > 0) {
                fPrice = fPrice * (Customer.oCustomer.info.custo_discount/100)
            }
            var oTdSum = oTr.find('td').eq(6);//total money td
            var fSumNum = parseFloat(fNum);
            var fSumJin = fSumNum * parseFloat(fPrice);
            oTdSum.html(fSumJin);//total money td
            oTdNum.html(fSumNum);//total product num
        } else {
            oTdNum.html("");
        }
        this.fnSumOrderTotalMoney();
    },
    _fnClearTableTr : function(rowId) {
        var oTr = $("#"+rowId);//tr
        var oProductTd = oTr.find('td').eq(2);
        var oProNumTd = oTr.find('td').eq(3);
        var oUnitTd = oTr.find('td').eq(4);
        var oPriceTd = oTr.find('td').eq(5);
        var oTotalJinTd = oTr.find('td').eq(6);
        oProductTd.html('');
        oProNumTd.html('');
        oUnitTd.html('');
        oPriceTd.html('');
        oTotalJinTd.html('');
        delete Order.tableList[rowId];
    }
};
Order.UI = {};
Order.UI.SearchSelect = {

    fnInt : function () {
        this.targetTd ={};
        this.targetSuggestWrap ={};
        this.targetInput ={};
        this.left =0;
        this.top =0;
        this.key ='';
        this.inputVal ='';
        this.url ='';
        this.leftPlus ='';
        this.topPlus ='';
        this.inputWith ='';
        this.fnHideSuggest = function(){};
        this.fnMousedown = function (that,obj){};
    },

    fnSendKeyWord : function(event){
        var that = this;
        var input = that.targetInput;
        var inputOffset = input.offset();
        var suggestWrap = that.targetSuggestWrap;
        //input = $(this);
        that.left = inputOffset.left+that.leftPlus;
        that.top = inputOffset.top+that.topPlus;
        if(suggestWrap.css('display')=='block' && event.keyCode == 38 || event.keyCode == 40 || event.keyCode == 13){
            var current = suggestWrap.find('li.cmail');
            if(event.keyCode == 38){
                if(current.length>0){
                    var prevLi = current.removeClass('cmail').prev();
                    if(prevLi.length>0){
                        prevLi.addClass('cmail');
                        input.val(prevLi.html());
                    }
                }else{
                    var last = suggestWrap.find('li:last');
                    last.addClass('cmail');
                    input.val(last.html());
                }

            }else if(event.keyCode == 40){
                if(current.length>0){
                    var nextLi = current.removeClass('cmail').next();
                    if(nextLi.length>0){
                        nextLi.addClass('cmail');
                        input.val(nextLi.html());
                    }
                }else{
                    var first = suggestWrap.find('li:first');
                    first.addClass('cmail');
                    input.val(first.html());
                }
            }else if(event.keyCode == 13){
                input.val(current.html());
                that.fnHideSuggest();
            }else{
                suggestWrap.hide();
            }

            //输入字符
        }else{
            var valText = $.trim(input.val());
            if(valText ==''||valText==that.key){
                that._fnSendKeyWordToBack(valText);
            } else {
                that._fnSendKeyWordToBack(valText);
                that.key = valText;
            }

        }

    },
    _fnSendKeyWordToBack : function(keyword){
        var that = this;
        var  obj = {};
        if (!keyword) {
            obj.type= 'all';
        } else {
            obj.keyword = keyword;
        }
        $.ajax(
            {
                type: "POST",
                url: that.url,
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    var aData = [];
                    for(var i=0;i<data.length;i++){
                        var objData = {};
                        if(data[i]){
                            objData.name = data[i].name;
                            objData.id = data[i].id;
                            aData.push(objData);
                        }
                    }
                    that._fnDataDisplay(aData);
                }
            }
        );
    },
    _fnDataDisplay : function(data){
        var that = this;
        var suggestWrap = that.targetSuggestWrap;
        if(data.length<=0){
            suggestWrap.hide();
            return;
        }

        //往搜索框下拉建议显示栏中添加条目并显示
        var li;
        var tmpFrag = document.createDocumentFragment();
        suggestWrap.find('ul').html('');
        for(var i=0; i<data.length; i++){
            li = document.createElement('LI');
            li.setAttribute("data-id",data[i].id);
            li.innerHTML = data[i].name;
            tmpFrag.appendChild(li);
        }
        suggestWrap.find('ul').append(tmpFrag);
        /**display: block; width: 683px; left: 236.567px; position: absolute; top: 144px; z-index: 958;*/
        suggestWrap.attr("style","width: "+that.inputWith+"px; left: "+that.left+"px; position: absolute; top: "+that.top+"px; z-index: 958;");
        suggestWrap.show();

        //为下拉选项绑定鼠标事件
        suggestWrap.find('li').hover(function(){
            suggestWrap.find('li').removeClass('cmail');
            $(this).addClass('cmail');

        },function(){
            $(this).removeClass('cmail');
        }).mousedown(function(){
                var oProObj = {};
                oProObj.name = this.innerHTML;
                oProObj.id = this.getAttribute('data-id');
                that.fnMousedown && that.fnMousedown(that,oProObj);
            });
    }

};
