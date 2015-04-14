/**
 * Created by zhangchao8189888 on 15-4-5.
 */
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
        onClick: onClick
    }
};

$(document).ready(function(){
    //initMyZtree();
    $.fn.zTree.init($("#treeDemo"), setting);
    zTree = $.fn.zTree.getZTreeObj("treeDemo");
    rMenu = $("#rMenu");
    $("#addZd").click(function(){
        if (!$('#department_id').val()) {
            alert('选择部门');
            return;
        }
        var data = {
            zd_name : $('#ziduanName').val(),
            zd_type : $('#ziduanType').val(),
            departId : $('#department_id').val()
        };
        $.ajax({
            type:'POST',
            url:'index.php?action=BaseData&mode=addZiduan',
            data: data,
            dataType:'Json',
            success:function(result){
                var html = '';
                var body = $('#ziduanBody');
                if (data.zd_type == 1) data.zd_type = '相加项';
                else if (data.zd_type == 2) data.zd_type = '相减项';
                if(result.code == 100000){
                    html = '<tr id="z_'+result+'" class="odd">' +
                        '<td>'+data.zd_name+'</td><td>'
                        + data.zd_type+'</td><td><a href="#" onclick="del('
                        +result.mess+
                        ')" class="btn btn-warning">删除</a></td></tr>';
                    body.append(html);
                } else {
                    alert(result.mess);
                }
            }
        })
    });
});
function onClick(e,treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    var data = zTree.getSelectedNodes()[0];
    if (!data.isParent) {
        $("#department_id").val(data.id);
        var data = {
            id : data.id
        }
        $.ajax({
            type:'POST',
            url:'index.php?action=BaseData&mode=getZiduanByIdJson',
            data: data,
            dataType:'Json',
            success:function(result){
                var html = '';
                var body = $('#ziduanBody');
                body.html('');
                if(result.length == 0){
                    html = '<tr class="odd"><td style="color:red">搜索结果为空</td></tr>'
                    body.append(html);
                    return false;
                }
                for(var i = 0; i<result.length;i++) {
                    var data = result[i];
                    html = '<tr id="z_'+data.id+'" class="odd">' +
                        '<td>'+data.zd_name+'</td><td>'
                        + data.zd_type+'</td><td><a href="#" onclick="del('+data.id+')" class="btn btn-warning">删除</a></td></tr>'
                    body.append(html);
                }


            }
        })
    } else {
        zTree.expandNode(treeNode);
    }

}
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
function del (id) {
    var data = {
        zid : id
    };
    $.ajax({
        type:'POST',
        url:'index.php?action=BaseData&mode=delZiduan',
        data: data,
        dataType:'Json',
        success:function(result){
            if(result.code == 100000) {
                $("#z_"+id).remove();
            } else {
                alert(result.mess);
            }
        }
    })
}