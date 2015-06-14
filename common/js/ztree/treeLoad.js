var  setting = {
	view: {
				dblClickExpand: false,
				showLine: true
			},
	async: {
		enable: true,
		url:"index.php?action=BaseData&mode=getDepartmentTreeJson",
		autoParam:["id", "name=n", "level=lv"],
        dataType:'json',
		otherParam:{company_id:$("#company_id").val()},
		dataFilter: filter,
		type: "post"
	},
	callback: {
		beforeAsync: beforeAsync,
		onAsyncSuccess: onAsyncSuccess,
		onAsyncError: onAsyncError,
		onRightClick: OnRightClick,
		onClick: onClick
	}
};
$(document).ready(function(){
    //initMyZtree();
    $.fn.zTree.init($("#treeDemo"), setting);
    zTree = $.fn.zTree.getZTreeObj("treeDemo");
    rMenu = $("#rMenu");
    hideRMenu();
    $('.close').click(function(){
        $('.modal-backdrop').hide();
        $('.modal').hide();
        $('#modal_del').hide();
    });
    $('#treeAdd').click(function(){
        var creatName = $('input[name=creatName]').val(),
            creatSort = isNaN(parseInt($('input[name=creatSort]').val())) ? 0 : $('input[name=creatSort]').val(),
            id = zTree.getSelectedNodes()[0].id;
        var data = {
            id:id,
            name:creatName,
            sort_order:creatSort
        }
        $.ajax({
            type: "POST",
            url: "index.php?action=BaseData&mode=addDepartmentTreeJson",
            data: data,
            dataType:'json',
            success: function(msg){
                if (zTree.getSelectedNodes()[0]) {
                    zTree.addNodes(zTree.getSelectedNodes()[0], {id:msg.data.id, name:creatName + '('+creatSort+')', sort_order:creatSort});
                } else {
                    zTree.addNodes(null, {id:msg.data.id, name:creatName + '('+creatSort+')'});
                }
                $('.modal-backdrop').hide();
                $('#modal_create').hide();
            }

        })
    });
    $('#emp_add').click(function(){
        var nodeData=Array();
        var strId ='';
        $("input[name='timeList']:checkbox:checked").each(function(){
            var obj = {};
            strId+=$(this).val()+",";
            obj.id = $(this).val();
            obj.name = $(this).attr("data-name");
            nodeData.push(obj);
        })
        var data = {
            ids:strId,
            id:zTree.getSelectedNodes()[0].id
        }
        $.ajax({
            type: "POST",
            url: "index.php?action=BaseData&mode=addEmployTreeJson",
            data: data,
            dataType:'json',
            success: function(msg){
                if (zTree.getSelectedNodes()[0]) {
                    //zTree.addNodes(zTree.getSelectedNodes()[0], {id:msg.data.id, name:creatName + '('+creatSort+')', sort_order:creatSort});
                    for(var key in nodeData){
                        var obj = nodeData[key];
                        zTree.addNodes(zTree.getSelectedNodes()[0], {id:obj.id, name:obj.name,type:'isEmploy'});
                    }
                } else {
                    //zTree.addNodes(null, {id:msg.data.id, name:creatName + '('+creatSort+')'});
                }
                $('.modal-backdrop').hide();
                $('#employ_add').hide();
            }

        })
    });
    $('#treeEdit').click(function(){
        var editName = $('input[name=editName]').val(),
            editSort = $('input[name=editSort]').val(),
            id = zTree.getSelectedNodes()[0].id;
        var data = {
            id:id,
            name:editName,
            sort_order:editSort
        }
        $.ajax({
            type: "POST",
            url: "index.php?action=BaseData&mode=editDepartmentTreeJson",
            data: data,
            success: function(msg){

                //$('#'+zTree.getSelectedNodes()[0].tId+'_span').text(editName+'('+editSort+')');
                $('#'+zTree.getSelectedNodes()[0].tId+'_span');
                // console.log(zTree.getSelectedNodes()[0]);
                $('.modal-backdrop').hide();
                $('#modal_edit').hide();
            }

        })
    });
    /*搜索*/
    $('#classify').click(function(){
        var searchTxt = $(this).prev().val();
        var data = {
            name:searchTxt
        }
        if(searchTxt == ''){return false;}
        $.ajax({
            type:'POST',
            url:'./backend.php?r=sort/SearchSort&name',
            data: data,
            dataType:'Json',
            success:function(result){
                var html = '';
                $('.search_list tbody').html('');
                if(result.data == ''){
                    html = '<tr class="odd"><td style="color:red">搜索结果为空</td></tr>'
                    $('.search_list tbody').append(html);
                    return false;
                }
                $.each(result.data, function(i,item ){
                    var regExp = new RegExp(searchTxt,'g');
                    var newName = item.name.replace(regExp,'<span style="color:red">'+searchTxt+'</span>');

                    html = '<tr class="odd"><td>' + newName+'</td></tr>'
                    $('.search_list tbody').append(html);
                });

            }
        })
    })
    /*删除*/
    $('#treeDel').on('click',function(){
        var id = zTree.getSelectedNodes()[0].id;
        var data = {
            id:id
        }
        $.ajax({
            type: "POST",
            url: "index.php?action=BaseData&mode=delDepartmentTreeJson",
            data: data,
            dataType:'json',
            success: function(data){
                if(data.code== 100100) {
                    $('#modal_del').hide();
                    showTips(data.msg);

                    return false;
                }
                zTree.removeNode(zTree.getSelectedNodes()[0]);
                $('.modal-backdrop').hide();
                $('#modal_del').hide();
            }

        })
    })
});
function filter(treeId, parentNode, childNodes) {
	if (!childNodes) return null;
	for (var i=0, l=childNodes.length; i<l; i++) {
		childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
	}
	return childNodes;
}

function beforeAsync() {
	curAsyncCount++;
}
var firstAsyncSuccessFlag = 0;
function onAsyncSuccess(event, treeId, treeNode, msg) {
	if (firstAsyncSuccessFlag == 0) {   
          try {   
				var selectedNode = zTree.getSelectedNodes();   
                 var nodes = zTree.getNodes();   
                 zTree.expandNode(nodes[0], true);   
                 var childNodes = zTree.transformToArray(nodes[0]);   
                 zTree.expandNode(childNodes[1], true);   
                 zTree.selectNode(childNodes[1]);   
                 var childNodes1 = zTree.transformToArray(childNodes[1]);   
                 zTree.checkNode(childNodes1[1], true, true);   
                 firstAsyncSuccessFlag = 1;   
           } catch (err) {   

           }   
     }  
	curAsyncCount--;
	if (curStatus == "expand") {
		expandNodes(treeNode.children);
	} else if (curStatus == "async") {
		asyncNodes(treeNode.children);
	}

	if (curAsyncCount <= 0) {
		if (curStatus != "init" && curStatus != "") {
			$("#demoMsg").text((curStatus == "expand") ? demoMsg.expandAllOver : demoMsg.asyncAllOver);
			asyncForAll = true;
		}
		curStatus = "";
	}
}

function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
	curAsyncCount--;

	if (curAsyncCount <= 0) {
		curStatus = "";
		if (treeNode!=null) asyncForAll = true;
	}
}

var curStatus = "init", curAsyncCount = 0, asyncForAll = false,
goAsync = false;

function expandNodes(nodes) {
	if (!nodes) return;
	curStatus = "expand";
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	for (var i=0, l=nodes.length; i<l; i++) {
		zTree.expandNode(nodes[i], true, false, false);
		if (nodes[i].isParent && nodes[i].zAsync) {
			expandNodes(nodes[i].children);
		} else {
			goAsync = true;
		}
	}
}


function asyncNodes(nodes) {
	if (!nodes) return;
	curStatus = "async";
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	for (var i=0, l=nodes.length; i<l; i++) {
		if (nodes[i].isParent && nodes[i].zAsync) {
			asyncNodes(nodes[i].children);
		} else {
			goAsync = true;
			zTree.reAsyncChildNodes(nodes[i], "refresh", true);
		}
	}
}

function OnRightClick(event, treeId, treeNode) {
	if(!treeNode){return false}
	if (!treeNode  || treeNode.lft == '1') {
		// if (!treeNode && event.target.tagName.toLowerCase() != "button" && $(event.target).parents("a").length == 0) {
		zTree.selectNode(treeNode);
		showRMenu("root", event.clientX-220, event.clientY-77);
	} else if (treeNode && !treeNode.noR) {
		zTree.selectNode(treeNode);
		showRMenu("node", event.clientX-220, event.clientY-77);
	}
}
function showRMenu(type, x, y) {
	$("#rMenu ul").show();
	if (type=="root") {
	//	$('#rMenu').css({'top':'66px !important'});
		$("#m_del").hide();
		$('#m_edit').hide();
	} else {
		$("#m_del").show();
		$('#m_edit').show();
	}
	rMenu.css({"top":y+"px", "left":x+"px", "visibility":"visible"});

	$("body").bind("mousedown", onBodyMouseDown);
}
function hideRMenu() {
	if (rMenu) rMenu.css({"visibility": "hidden"});
	$("body").unbind("mousedown", onBodyMouseDown);
}
function onBodyMouseDown(event){
	if (!(event.target.id == "rMenu" || $(event.target).parents("#rMenu").length>0)) {
		rMenu.css({"visibility" : "hidden"});
	}
}
var addCount = 1;
function addTreeNode() {
	hideRMenu();
	$('input[name=creatName]').val('');
	$('input[name=creatSort]').val('');
	$('.modal-backdrop').show();
	$('#modal_create').show();
}
function employ_add () {
    hideRMenu();
    $('#tbodays').html('');
    $.ajax({
        type:'POST',
        url:'index.php?action=BaseData&mode=getEmployJson',
        data: {},
        dataType:'Json',
        success:function(result){
            var html = '';
            $('#tbodays').html('');
            if(result == ''){
                html = '<tr class="odd"><td style="color:red">搜索结果为空</td></tr>'
                $('.search_list tbody').append(html);
                return false;
            }
            $.each(result, function(i,item ){
                var newName = item.eName;
                var eid = item.id;
                var eno = item.eNo;

                html = '<tr class="odd"><td><input type="checkbox" data-name ="'+newName+'"  name="timeList" value="'+eid+'" ></td><td>' + newName+'</td><td>' + eno+'</td></tr>'
                $('#tbodays').append(html);
            });

        }
    })
    $('.modal-backdrop').show();
    $('#employ_add').show();
}
function editTreeNode(){
	var parent = zTree.getSelectedNodes()[0];
	var sortName= parent.name.split("(");
	$('input[name=editName]').val($.trim(sortName[0].toString()));
	$('input[name=editSort]').val(parent.sort_order);
	hideRMenu();
	$('.modal-backdrop').show();
	$('#modal_edit').show();
}
function removeTreeNode() {
	hideRMenu();
	$('.modal-backdrop').show();
	$('#modal_del').show();
	
}
function onClick(e,treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    var data = zTree.getSelectedNodes()[0];
    if (!data.isParent) {
        var data = {
            id : data.id
        }
        $.ajax({
            type:'POST',
            url:'index.php?action=BaseData&mode=getEmployByDeparIdJson',
            data: data,
            dataType:'Json',
            success:function(result){
                var html = '';
                var body = $('#employBody');
                body.html('');
                if(!result.data ||  result.data.length == 0){
                    html = '<tr class="odd"><td style="color:red" colspan="5">搜索结果为空</td></tr>'
                    body.append(html);
                    return false;
                }
                for(var i = 0; i < result.data.length; i++) {
                    var dataE = result.data[i];
                    html = '<tr class="odd">' +
                        '<td>'+dataE.e_name+'</td><td>'
                        + dataE.e_num+'</td><td>' +
                        '' + dataE.e_type+'</td><td>' +
                        '' + dataE.e_hetong_start+'</td><td>' + dataE.e_hetong_end+'</td></tr>'
                    body.append(html);
                }


            }
        })
    } else {
        zTree.expandNode(treeNode);
    }

}

function showTips(msg){
	$('.modal-backdrop').show();
	$('#modal_tips .modal-body').html(msg);
	$('#modal_tips').show();
}