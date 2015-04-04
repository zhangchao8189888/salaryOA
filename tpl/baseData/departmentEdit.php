
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Company&mode=toDepartmentEdit">部门编辑</a>
        </div>
    </div>
    <div class="container-fluid">
    <div class="search-form">
        <div class="row-fluid1">
            <div class="search">
                公司名称：<input type="text" maxlength="20" id="e_company"name="e_company" autocomplete="off" /><input type="hidden" value="" id="company_id" name="company_id"/>
                <input type="submit" value="搜索" name="yt0" class="btn btn-success" id="classify">
            </div>
        </div>
    </div>
    <?php
    /* @var $this sortController */
    /* @var $model Sort */

    $this->breadcrumbs=array(
        '分类',
    );

    ?>
    <div class="tree_l">


        <div class="zTreeDemoBackground left">
            <ul id="treeDemo" class="ztree"></ul>
        </div>
        <div id="rMenu">
            <ul>
                <a id="m_add" class="btn btn-success btn-mini" onclick="addTreeNode();">增加部门</a><br/>
                <a id="m_edit" class="btn btn-warning btn-mini"  onclick="editTreeNode();">编辑部门</a><br/>
                <a id="m_del" class="btn btn-danger btn-mini" onclick="removeTreeNode();">删除部门</a><br/>
<!--                <a id="m_add" class="btn btn-success btn-mini" onclick="employ_add();">添加员工</a><br/>-->
<!--                <a id="m_del" class="btn btn-danger btn-mini" onclick="removeTreeNode();">删除员工</a><br/>-->
            </ul>
        </div>

    </div>
    <div class="search_r">

        <div class="search_list">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><div>姓名</div></th>
                    <th><div>单位名称</div></th>
                    <th><div>身份证号</div></th>
                    <th><div>身份类别</div></th>
                    <th><div>合同期限</div></th>
                    <th><div>合同日期</div></th>
                </tr>
                </thead>
                <tbody id ='employBody'>

                </tbody>
            </table>
        </div>
    </div>
    <link href='common/js/ztree/zTreeStyle/zTreeStyle.css' rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="common/js/ztree/jquery.ztree.core-3.5.js"></script>
    <script type="text/javascript" src="common/js/ztree/treeLoad.js"></script>

    <div class="modal-backdrop  in" style="display:none"></div>
    <div data-id="" class="modal hide in" id="modal_create" aria-hidden="false">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>设置属性</h3>
        </div>
        <div id="thumbnails_body" class="modal-body">
            <div class="modal_list">
                <span>部门名称</span><input type="text" class="span3" name="creatName">
            </div>
            <!--<div class="modal_list">
                <span>排序</span><input type="text" class="span3" name="creatSort">
            </div>-->
            <div class="modal_btn">
                <input type="button" class="btn" value="确定" id="treeAdd"/>
                <input type="button" class="btn close" value="取消"/>
            </div>
        </div>
    </div>

    <div data-id="" class="modal hide in" id="modal_edit" aria-hidden="false">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>设置属性</h3>
        </div>
        <div id="thumbnails_body" class="modal-body">
            <div class="modal_list">
                <span>部门名称</span><input type="text" class="span3" name="editName">
            </div>
            <!--<div class="modal_list">
                <span>排序</span><input type="text" class="span3" name="editSort">
            </div>-->
            <div class="modal_btn">
                <input type="button" class="btn" value="确定" id="treeEdit"/>
                <input type="button" class="btn close" value="取消"/>
            </div>
        </div>
    </div>
    <div data-id="" class="modal hide in" id="employ_add" aria-hidden="false">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>添加员工</h3>
        </div>
        <div id="thumbnails_body" class="modal-body">
            <div class="employ_list">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><div>选择</div></th>
                        <th><div>姓名</div></th>
                        <th><div>身份证号</div></th>
                    </tr>
                    </thead>
                    <tbody  id="tbodays">
                    </tbody>
                </table>
            </div>
            <!--<div class="modal_list">
                <span>排序</span><input type="text" class="span3" name="editSort">
            </div>-->
            <div class="modal_btn">
                <input type="button" class="btn" value="确定" id="emp_add"/>
                <input type="button" class="btn close" value="取消"/>
            </div>
        </div>
    </div>
    <div data-id="" class="modal hide in" id="modal_del" aria-hidden="false">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>删除信息</h3>
        </div>
        <div id="thumbnails_body" class="modal-body">
            <p>确认要删除该部门吗？</p>
            <div class="modal_btn">
                <input type="button" class="btn" value="确定" id="treeDel"/>
                <input type="button" class="btn close" value="取消"/>
            </div>
        </div>
    </div>
    <div data-id="" class="modal hide in" id="modal_tips" aria-hidden="false">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>提示信息</h3>
        </div>
        <div id="thumbnails_body" class="modal-body">

        </div>
    </div>
    </div>
</div>