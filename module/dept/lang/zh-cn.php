<?php
/**
 * The dept module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->dept->common      = '部门结构';
$lang->dept->manageChild = "下级部门";
$lang->dept->edit        = "编辑部门";
$lang->dept->delete      = "删除部门";
$lang->dept->parent      = "上级部门";
$lang->dept->manager     = "负责人";
$lang->dept->name        = "部门名称";
$lang->dept->browse      = "部门维护";
$lang->dept->manage      = "维护部门结构";
$lang->dept->updateOrder = "更新排序";
$lang->dept->add         = "添加部门";
$lang->dept->dragAndSort = "拖动排序";

$lang->dept->confirmDelete = " 您确定删除该部门吗？";
$lang->dept->successSave   = " 修改成功。";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = '该部门有子部门，不能删除！';
$lang->dept->error->hasUsers = '该部门有职员，不能删除！';
