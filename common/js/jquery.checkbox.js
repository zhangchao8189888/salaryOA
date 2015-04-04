/**
 * checkbox 全选操作
 *
 * @author     Akon(番茄红了) <aultoale@gmail.com>
 * @copyright  Copyright (c) 2008 (http://www.tblog.com.cn)
 * @license    http://www.gnu.org/licenses/gpl.html     GPL 3
 *
 * @example $('input[@type=checkbox][@name=checkAll]').checkbox();
 * 自动切换 : .toggle(element)
 * 全选 : .checked(element)
 * 反选 : .unchecked(element)
 * 获取字符串值 : .val()
 */

$.fn.checkbox = function(){

    var hand = this;

    /**
     * 切换全选/反选
     *
     * @example .toggle($('input[@type=checkbox][@name=id]'))
     */
    this.toggle = function(ele){
        $(ele).click(function(){
            $(hand).attr('checked', false);
        });
        $(this).click(function(){
            $(ele).attr('checked', $(this).attr('checked') == true ? true : false);
        });
    };

    /**
     * 全选
     */
    this.checked = function(ele){
        $(ele).attr('checked', true);
    };

    /**
     * 反选
     */
    this.unchecked = function(ele){
        $(ele).attr('checked', false);
    };

    /**
     * 获取已选中值, 并以字符串返回数据
     */
    this.val = function(){
        var string = '';
        $(this).each(function(){
            if ($(this).attr('checked') == true && $(this).val()) {
                if (string) {
                    string += ',';
                }
                string += $(this).val();
            };
        });
        return string;
    };

    return this;

};