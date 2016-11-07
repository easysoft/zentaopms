<?php
/**
 * The tree module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: en.php 5045 2013-07-06 07:04:40Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->tree = new stdclass();
$lang->tree->common            = 'Module';
$lang->tree->edit              = 'Edit';
$lang->tree->delete            = 'Delete';
$lang->tree->browse            = 'General Module';
$lang->tree->browseTask        = 'Task Module';
$lang->tree->manage            = 'Module';
$lang->tree->fix               = 'Fix';
$lang->tree->manageProduct     = "Manage {$lang->productCommon} Module";
$lang->tree->manageProject     = "Manage {$lang->projectCommon} Project";
$lang->tree->manageBug         = 'Manage Bug Module';
$lang->tree->manageCase        = 'Manage Case Module';
$lang->tree->manageCustomDoc   = 'Manage DocLib Type';
$lang->tree->updateOrder       = 'Update Order';
$lang->tree->manageChild       = 'Manage Child Module';
$lang->tree->manageStoryChild  = 'Manage Child Story';
$lang->tree->manageBugChild    = 'Manage Child Bug';
$lang->tree->manageCaseChild   = 'Manage Child Case';
$lang->tree->manageTaskChild   = "Manage Child {$lang->projectCommon}";
$lang->tree->syncFromProduct   = 'Copy';
$lang->tree->dragAndSort       = "Drag and Sort";
$lang->tree->addChild          = "Add Child Module";

$lang->tree->confirmDelete = 'Do you want to delete this Module and its Child?';
$lang->tree->confirmRoot   = "Modify the module {$lang->productCommon}s, modified the {$lang->productCommon}s belong to the needs of the module, bug, use case, and {$lang->projectCommon} and {$lang->productCommon} relationship. This is dangerous, please be careful. To confirm the change?";
$lang->tree->successSave   = 'Successfully saved';
$lang->tree->successFixed  = 'Successfully fixed.';
$lang->tree->repeatName    = 'The name "%s" has exists!';

$lang->tree->name       = 'Name';
$lang->tree->parent     = 'Parent';
$lang->tree->child      = 'Child';
$lang->tree->owner      = 'Owner';
$lang->tree->order      = 'Order';
$lang->tree->short      = 'Sort';
$lang->tree->all        = 'All Modules';
$lang->tree->projectDoc = "{$lang->projectCommon} Doc";
$lang->tree->product    = $lang->productCommon;
