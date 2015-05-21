<?php
/**
 * The tree module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: zh-cn.php 4836 2013-06-19 05:39:40Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->tree = new stdclass();
$lang->tree->common            = '模块维护';
$lang->tree->add               = '添加';
$lang->tree->edit              = '编辑';
$lang->tree->addChild          = '添加子模块';
$lang->tree->delete            = '删除模块';
$lang->tree->browse            = '通用模块维护';
$lang->tree->browseTask        = '任务模块维护';
$lang->tree->manage            = '维护模块';
$lang->tree->fix               = '修正数据';
$lang->tree->manageProduct     = "维护{$lang->productcommon}视图模块";
$lang->tree->manageProject     = "维护{$lang->projectcommon}视图模块";
$lang->tree->manageBug         = '维护测试视图模块';
$lang->tree->manageCase        = '维护用例视图模块';
$lang->tree->manageCustomDoc   = '维护文档库分类';
$lang->tree->updateOrder       = '更新排序';
$lang->tree->manageChild       = '维护子模块';
$lang->tree->manageStoryChild  = '维护子模块';
$lang->tree->manageBugChild    = '维护Bug子模块';
$lang->tree->manageCaseChild   = '维护用例子模块';
$lang->tree->manageTaskChild   = "维护{$lang->projectcommon}子模块";
$lang->tree->syncFromProduct   = '复制';
$lang->tree->ajaxGetOptionMenu = '接口:获取下拉列表';
$lang->tree->ajaxGetSonModules = '接口:获得子菜单列表';

$lang->tree->confirmDelete = '该模块及其子模块都会被删除，您确定删除吗？';
$lang->tree->confirmRoot   = "模块的所属{$lang->productcommon}修改，会关联修改该模块下的需求、Bug、用例的所属{$lang->productcommon}，以及{$lang->projectcommon}和{$lang->productcommon}的关联关系。该操作比较危险，请谨慎操作。是否确认修改？";
$lang->tree->successSave   = '成功保存';
$lang->tree->successFixed  = '成功修正数据！';

$lang->tree->name       = '模块名称';
$lang->tree->parent     = '上级模块';
$lang->tree->child      = '子模块';
$lang->tree->owner      = '负责人';
$lang->tree->order      = '排序';
$lang->tree->projectDoc = "{$lang->projectcommon}文档";
$lang->tree->product    = "所属{$lang->productcommon}";
