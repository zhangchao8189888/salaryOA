BaseWidget ={};
BaseWidget.UI = {};
BaseWidget.UI.SearchSelect = {

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
        var inputOffset = input.position();
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

};/**
 * Created by chaozhang204017 on 15-1-9.
 */
