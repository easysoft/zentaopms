<?php
/**
 * The dept module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
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
$lang->dept->browse       = "Dept Settings";
$lang->dept->manage       = "Dept Settings";
$lang->dept->updateOrder  = "Sort Depts";
$lang->dept->add          = "Add Department";
$lang->dept->grade        = "Depts Level";
$lang->dept->order        = "Sort";
$lang->dept->dragAndSort  = "Drag to Reorder";
$lang->dept->noDepartment = "No department.";

$lang->dept->manageChildAction = "Manage Sub-departments";

$lang->dept->confirmDelete = "Are you sure you want to delete this department?";
$lang->dept->successSave   = "Updated successfully.";
$lang->dept->repeatDepart  = "A department with this name already exists. Are you sure you want to add it?";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = 'This department has sub-departments and cannot be deleted.';
$lang->dept->error->hasUsers = 'This department has employees and cannot be deleted.';
