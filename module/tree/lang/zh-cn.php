<?php
/**
 * The tree module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: zh-cn.php 4836 2013-06-19 05:39:40Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->tree                       = new stdclass();
$lang->tree->common               = '模块维护';
$lang->tree->edit                 = '编辑模块';
$lang->tree->delete               = '删除模块';
$lang->tree->browse               = '通用模块维护';
$lang->tree->browseTask           = '任务模块维护';
$lang->tree->manage               = '维护模块';
$lang->tree->fix                  = '修正数据';
$lang->tree->manageProduct        = "维护{$lang->productCommon}视图模块";
$lang->tree->manageProject        = "维护{$lang->projectCommon}视图模块";
$lang->tree->manageExecution      = "维护{$lang->execution->common}视图模块";
$lang->tree->manageLine           = "维护产品线";
$lang->tree->manageBug            = '维护测试视图模块';
$lang->tree->manageCase           = '维护用例视图模块';
$lang->tree->manageCaseLib        = '维护用例库模块';
$lang->tree->manageCustomDoc      = '维护文档库分类';
$lang->tree->manageApiChild       = '维护接口库目录';
$lang->tree->updateOrder          = '更新排序';
$lang->tree->manageChild          = '维护子模块';
$lang->tree->manageStoryChild     = '维护子模块';
$lang->tree->manageLineChild      = "维护产品线";
$lang->tree->manageBugChild       = '维护Bug子模块';
$lang->tree->manageCaseChild      = '维护用例子模块';
$lang->tree->manageCaselibChild   = '维护用例库子模块';
$lang->tree->manageDashboard      = '维护仪表盘模块';
$lang->tree->manageDashboardChild = '维护仪表盘子模块';
$lang->tree->manageProjectChild   = "维护{$lang->projectCommon}子模块";
$lang->tree->manageTaskChild      = "维护{$lang->execution->common}子模块";
$lang->tree->manageTaskChild      = "维护{$lang->execution->common}子模块";
$lang->tree->syncFromProduct      = '复制模块';
$lang->tree->dragAndSort          = "拖放排序";
$lang->tree->sort                 = "排序";
$lang->tree->addChild             = "增加子模块";
$lang->tree->confirmDelete        = '该模块及其子模块都会被删除，您确定删除吗？';
$lang->tree->confirmDeleteMenu    = '该目录及其子目录都会被删除，您确定删除吗？';
$lang->tree->confirmDelCategory   = '该分类及其子分类都会被删除，您确定删除吗？';
$lang->tree->confirmDeleteLine    = "您确定删除该产品线吗？";
$lang->tree->confirmDeleteGroup   = '该分组及其子分组都会被删除，您确定删除吗？';
$lang->tree->confirmRoot          = "模块的所属{$lang->productCommon}修改，会关联修改该模块下的{$lang->SRCommon}、Bug、用例的所属{$lang->productCommon}，以及{$lang->executionCommon}和{$lang->productCommon}的关联关系。该操作比较危险，请谨慎操作。是否确认修改？";
$lang->tree->confirmRoot4Doc      = "修改所属文档库，会同时修改该分类下文档的关联关系。该操作比较危险，请谨慎操作。是否确认修改？";
$lang->tree->noSubmodule          = "当前模块下没有可复制的子模块！";
$lang->tree->successSave          = '成功保存';
$lang->tree->successFixed         = '成功修正数据！';
$lang->tree->repeatName           = '模块名“%s”已经存在！';
$lang->tree->repeatDirName        = '目录名“%s”已经存在！';
$lang->tree->shouldNotBlank       = '模块名不能为空格！';
$lang->tree->syncProductModule    = "同步{$lang->productCommon}模块";
$lang->tree->host                 = '主机';
$lang->tree->editHost             = '编辑主机分组';
$lang->tree->deleteHost           = '删除主机分组';
$lang->tree->manageHostChild      = '维护主机子分组';
$lang->tree->groupMaintenance     = '维护主机分组';
$lang->tree->groupName            = '分组名称';
$lang->tree->parentGroup          = '上级分组';
$lang->tree->childGroup           = '子分组';
$lang->tree->confirmDeleteHost    = '该分组及子分组都会被删除，您确定删除吗？';

$lang->tree->module       = '模块';
$lang->tree->name         = '模块名称';
$lang->tree->wordName     = '名称';
$lang->tree->line         = "产品线名称";
$lang->tree->cate         = '分类名称';
$lang->tree->dir          = '目录名称';
$lang->tree->root         = "所属{$lang->productCommon}";
$lang->tree->branch       = '平台/分支';
$lang->tree->path         = '路径';
$lang->tree->type         = '类型';
$lang->tree->parent       = '上级模块';
$lang->tree->parentCate   = '上级目录';
$lang->tree->child        = '子模块';
$lang->tree->parentGroup  = '上级分组';
$lang->tree->childGroup   = '子分组';
$lang->tree->subCategory  = '子分类';
$lang->tree->editCategory = '编辑分类';
$lang->tree->delCategory  = '删除分类';
$lang->tree->lineChild    = "子产品线";
$lang->tree->owner        = '负责人';
$lang->tree->order        = '排序';
$lang->tree->short        = '简称';
$lang->tree->all          = '所有模块';
$lang->tree->executionDoc = "{$lang->executionCommon}文档";
$lang->tree->product      = "所属{$lang->productCommon}";
$lang->tree->editDir      = "编辑目录";

$lang->tree->emptyHistory = "暂时没有历史记录。";

$lang->module = new stdclass();
$lang->module->action = new stdclass();
$lang->module->action->created = array('main' => "\$date, 由 <strong>\$actor</strong> 创建了 <strong>\$extra</strong>。");
$lang->module->action->moved   = array('main' => "\$date, 由 <strong>\$actor</strong> 移动了 <strong>\$extra</strong>。");
$lang->module->action->deleted = array('main' => "\$date, 由 <strong>\$actor</strong> 删除了 <strong>\$extra</strong>。");
