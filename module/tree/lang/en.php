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
$lang->tree->common            = 'Module manage';
$lang->tree->add               = 'Add';
$lang->tree->edit              = 'Edit';
$lang->tree->addChild          = 'Add child';
$lang->tree->delete            = 'Delete';
$lang->tree->browse            = 'Manage general module';
$lang->tree->browseTask        = 'Manage task module';
$lang->tree->manage            = 'Modules';
$lang->tree->fix               = 'Fix';
$lang->tree->manageProduct     = "Mange {$lang->productCommon} module";
$lang->tree->manageProject     = "Manage {$lang->projectCommon} module";
$lang->tree->manageBug         = 'Manage bug module';
$lang->tree->manageCase        = 'Manage case module';
$lang->tree->manageCustomDoc   = 'Manage doc library type';
$lang->tree->updateOrder       = 'Update order';
$lang->tree->manageChild       = 'Manage child';
$lang->tree->manageStoryChild  = 'Manage child';
$lang->tree->manageBugChild    = 'Manage bug child';
$lang->tree->manageCaseChild   = 'Manage case child';
$lang->tree->manageTaskChild   = "Manage {$lang->projectCommon} child";
$lang->tree->syncFromProduct   = 'Copy';
$lang->tree->ajaxGetOptionMenu = 'API: Get select menu';
$lang->tree->ajaxGetSonModules = 'API: Get son modules';

$lang->tree->confirmDelete = 'Are you sure to delete this module and its children?';
$lang->tree->confirmRoot   = "Modify the module {$lang->productCommon}s, modified the {$lang->productCommon}s belong to the needs of the module, bug, use case, and {$lang->projectCommon} and {$lang->productCommon} relationship. This is dangerous, please be careful. To confirm the change?";
$lang->tree->successSave   = 'Successfully saved';
$lang->tree->successFixed  = 'Successfully fixed.';

$lang->tree->name       = 'Name';
$lang->tree->parent     = 'Parent';
$lang->tree->child      = 'Child';
$lang->tree->owner      = 'Owner';
$lang->tree->order      = 'Order';
$lang->tree->projectDoc = "{$lang->projectCommon} doc";
$lang->tree->product    = $lang->productCommon;
