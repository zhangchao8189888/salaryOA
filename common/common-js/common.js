/**
 * Created by zhangchao8189888 on 15-5-21.
 */
function checkRate(input)
{
    var re = /^[0-9]+.?[0-9]*$/;   //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/
    var nubmer = document.getElementById(input).value;

    if (!re.test(nubmer))
    {
        alert("请输入数字");
        document.getElementById(input).value = "";
        return false;
    }
}
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
var UTIL = {
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
    },
    checkRate : function (val)
    {
        var re = /^[0-9]+.?[0-9]*$/;   //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/
        var nubmer = val;

        if (!re.test(nubmer))
        {
            return false;
        }
        return true;
    }
};
