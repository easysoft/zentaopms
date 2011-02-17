<?php
/**
 * The product module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->product->common = 'Product';
$lang->product->index  = "Index";
$lang->product->browse = "Browse";
$lang->product->view   = "Info";
$lang->product->edit   = "Edit";
$lang->product->create = "Create";
$lang->product->read   = "Info";
$lang->product->delete = "Delete";

$lang->product->roadmap   = 'Roadmap';
$lang->product->doc       = 'Coc';

$lang->product->selectProduct   = "Select product";
$lang->product->saveButton      = " Save (S) ";
$lang->product->confirmDelete   = " Are you sure to delete this product?";
$lang->product->ajaxGetProjects = "API: projects of product";
$lang->product->ajaxGetPlans    = "API: plans of product";

$lang->product->errorFormat    = 'Error format.';
$lang->product->errorEmptyName = 'Name can not be empty.';
$lang->product->errorEmptyCode = 'Code can not be empty';
$lang->product->errorNoProduct = 'No product in system yet.';
$lang->product->accessDenied   = 'Access to this product denined.';

$lang->product->id        = 'ID';
$lang->product->company   = 'Company';
$lang->product->name      = 'Name';
$lang->product->code      = 'Code';
$lang->product->order     = 'Order';
$lang->product->status    = 'Status';
$lang->product->desc      = 'Desc';
$lang->product->PO        = 'Product owner';
$lang->product->QM        = 'QA manager';
$lang->product->RM        = 'Release manager';
$lang->product->acl       = 'Access limitation';
$lang->product->whitelist = 'Whitelist';

$lang->product->moduleStory = 'By module';
$lang->product->searchStory = 'By search';
$lang->product->allStory    = 'All story';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Closed';

$lang->product->aclList['open']    = 'Default(Having product module prividge, can visit this product)';
$lang->product->aclList['private'] = 'Private(Only project team members can visit)';
$lang->product->aclList['custom']  = 'Whitelist(Project team members and who belongs to the whilelist groups can visit)';
