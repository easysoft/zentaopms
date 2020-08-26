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
$lang->tree->edit               = 'Edit Module';
$lang->tree->delete             = 'Delete Module';
$lang->tree->browse             = 'Manage General Module';
$lang->tree->browseTask         = 'Manage Task Module';
$lang->tree->manage             = 'Manage Module';
$lang->tree->fix                = 'Fix Module';
$lang->tree->manageProduct      = "Manage {$lang->productCommon} Modules";
$lang->tree->manageProject      = "Manage {$lang->projectCommon} Modules";
$lang->tree->manageLine         = "Manage {$lang->productCommon} Line";
$lang->tree->manageBug          = 'Manage Bug';
$lang->tree->manageCase         = 'Manage Case';
$lang->tree->manageCaseLib      = 'Manage Library';
$lang->tree->manageCustomDoc    = 'Manage Document Library';
$lang->tree->updateOrder        = 'Rank Module';
$lang->tree->manageChild        = 'Manage Child Modules';
$lang->tree->manageStoryChild   = 'Manage Child Modules';
$lang->tree->manageLineChild    = "Manage {$lang->productCommon} Line";
$lang->tree->manageBugChild     = 'Manage Child Bugs';
$lang->tree->manageCaseChild    = 'Manage Child Cases';
$lang->tree->manageCaselibChild = 'Manage Child Libraries';
$lang->tree->manageTaskChild    = "Manage Child {$lang->projectCommon} Modules";
$lang->tree->syncFromProduct    = "Copy from Other {$lang->productCommon}s";
$lang->tree->dragAndSort        = "Drag to order";
$lang->tree->sort               = "Order";
$lang->tree->addChild           = "Add Child Module";
$lang->tree->confirmDelete      = 'Do you want to delete this module and its child modules?';
$lang->tree->confirmDeleteLine  = "Do you want to delete this {$lang->productCommon} line?";
$lang->tree->confirmRoot        = "Any changes to the {$lang->productCommon} will change the stories, bugs, cases of {$lang->productCommon} it belongs to, as well as the linkage of {$lang->projectCommon} and {$lang->productCommon}, which is dangerous. Do you want to change it?";
$lang->tree->confirmRoot4Doc    = "Any changes to the library will change the document of library it belongs to, which is dangerous. Do you want to change it?";
$lang->tree->successSave        = 'Saved.';
$lang->tree->successFixed       = 'Fixed.';
$lang->tree->repeatName         = 'The name "%s" exists!';
$lang->tree->shouldNotBlank     = 'Module name should not be blank!';

$lang->tree->module     = 'Module';
$lang->tree->name       = 'Name';
$lang->tree->line       = "{$lang->productCommon} Line";
$lang->tree->cate       = 'Category';
$lang->tree->root       = 'Root';
$lang->tree->branch     = 'Platform/Branch';
$lang->tree->path       = 'Path';
$lang->tree->type       = 'Type';
$lang->tree->parent     = 'Parent Module';
$lang->tree->parentCate = 'Parent Category';
$lang->tree->child      = 'Children';
$lang->tree->lineChild  = "Child {$lang->productCommon} Line";
$lang->tree->owner      = 'Owner';
$lang->tree->order      = 'Order';
$lang->tree->short      = 'Abbr.';
$lang->tree->all        = 'All Modules';
$lang->tree->projectDoc = "{$lang->projectCommon} Document";
$lang->tree->product    = $lang->productCommon;
