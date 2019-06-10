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
$lang->tree->common             = 'Module';
$lang->tree->edit               = 'Edit';
$lang->tree->delete             = 'Delete';
$lang->tree->browse             = 'Manage General Module';
$lang->tree->browseTask         = 'Manage Task Module';
$lang->tree->manage             = 'Manage Module';
$lang->tree->fix                = 'Fix';
$lang->tree->manageProduct      = "Manage {$lang->productCommon}";
$lang->tree->manageProject      = "Manage {$lang->projectCommon}";
$lang->tree->manageLine         = 'Manage Product Line';
$lang->tree->manageBug          = 'Manage Bug';
$lang->tree->manageCase         = 'Manage Case';
$lang->tree->manageCaseLib      = 'Manage Library';
$lang->tree->manageCustomDoc    = 'Manage Document Library';
$lang->tree->updateOrder        = 'Sort';
$lang->tree->manageChild        = 'Manage Child Modules';
$lang->tree->manageStoryChild   = 'Manage Child Modules';
$lang->tree->manageLineChild    = 'Manage Product Line';
$lang->tree->manageBugChild     = 'Manage Child Bugs';
$lang->tree->manageCaseChild    = 'Manage Child Cases';
$lang->tree->manageCaselibChild = 'Manage Child Libraries';
$lang->tree->manageTaskChild    = "Manage Child {$lang->projectCommon}s";
$lang->tree->syncFromProduct    = 'Copy Other Product Module';
$lang->tree->dragAndSort        = "Drag and Sort";
$lang->tree->sort               = "Sort";
$lang->tree->addChild           = "Add Child Module";
$lang->tree->confirmDelete      = 'Do you want to delete this module and its child modules?';
$lang->tree->confirmDeleteLine  = 'Do you want to delete this product line?';
$lang->tree->confirmRoot        = "Any changes to the {$lang->productCommon} will change the stories, bugs, cases of {$lang->productCommon} it belongs to, as well as the relation of {$lang->projectCommon} and {$lang->productCommon}, which is dangerous. Do you want to change it?";
$lang->tree->successSave        = 'Saved.';
$lang->tree->successFixed       = 'Fixed.';
$lang->tree->repeatName         = 'The name "%s" exists!';

$lang->tree->module     = 'Module';
$lang->tree->name       = 'Name';
$lang->tree->line       = 'Product Line';
$lang->tree->cate       = 'Category';
$lang->tree->root       = 'Root';
$lang->tree->branch     = 'Platform/Branch';
$lang->tree->path       = 'Path';
$lang->tree->type       = 'Type';
$lang->tree->parent     = 'Parent Module';
$lang->tree->parentCate = 'Parent Category';
$lang->tree->child      = 'Children';
$lang->tree->lineChild  = 'Child Product Line';
$lang->tree->owner      = 'Owner';
$lang->tree->order      = 'Order';
$lang->tree->short      = 'Abbr.';
$lang->tree->all        = 'All Modules';
$lang->tree->projectDoc = "{$lang->projectCommon} Document";
$lang->tree->product    = $lang->productCommon;
