<?php
/**
 * The dept module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->dept->id           = 'ID';
$lang->dept->path         = 'Path';
$lang->dept->position     = 'Position';
$lang->dept->manageChild  = "Department";
$lang->dept->edit         = "Edit Department";
$lang->dept->delete       = "Delete Department";
$lang->dept->parent       = "Parent Dept";
$lang->dept->manager      = "Manager";
$lang->dept->name         = "Department Name";
$lang->dept->browse       = "Manage Department";
$lang->dept->manage       = "Manage Department";
$lang->dept->updateOrder  = "Department Ranking";
$lang->dept->add          = "Add Department";
$lang->dept->grade        = "Department Grade";
$lang->dept->order        = "Department Order";
$lang->dept->dragAndSort  = "Drag to order";
$lang->dept->noDepartment = "No Department";

$lang->dept->manageChildAction = "Manage Subordinate Department";

$lang->dept->confirmDelete = " Do you want to delete this department?";
$lang->dept->successSave   = " Saved!";
$lang->dept->repeatDepart  = " There is a duplicate department name, are you sure to add it?";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = 'This Department has child departments. You cannot delete it!';
$lang->dept->error->hasUsers = 'This Department has users. You cannot delete it!';
