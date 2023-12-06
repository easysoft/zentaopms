<?php
/**
 * The dept module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->dept->id           = '编号';
$lang->dept->path         = '路径';
$lang->dept->position     = '职位';
$lang->dept->manageChild  = "下级部门";
$lang->dept->edit         = "编辑部门";
$lang->dept->delete       = "删除部门";
$lang->dept->parent       = "上级部门";
$lang->dept->manager      = "负责人";
$lang->dept->name         = "部门名称";
$lang->dept->browse       = "部门维护";
$lang->dept->manage       = "维护部门";
$lang->dept->updateOrder  = "部门排序";
$lang->dept->add          = "添加部门";
$lang->dept->grade        = "部门级别";
$lang->dept->order        = "排序";
$lang->dept->dragAndSort  = "拖动排序";
$lang->dept->noDepartment = "无部门";

$lang->dept->manageChildAction = "维护下级部门";

$lang->dept->confirmDelete = " 您确定删除该部门吗？";
$lang->dept->successSave   = " 修改成功。";
$lang->dept->repeatDepart  = " 存在部门名称重复，您确认添加吗？";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = '该部门有子部门，不能删除！';
$lang->dept->error->hasUsers = '该部门有职员，不能删除！';
