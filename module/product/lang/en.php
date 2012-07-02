<?php
/**
 * The product module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->product->common = 'Product';
$lang->product->index  = "Index";
$lang->product->browse = "Browse";
$lang->product->dynamic= "Dynamic";
$lang->product->view   = "Info";
$lang->product->edit   = "Edit";
$lang->product->create = "Create";
$lang->product->read   = "Info";
$lang->product->delete = "Delete";
$lang->product->select = '--select product--';

$lang->product->basicInfo = 'Basic info';
$lang->product->otherInfo = 'Other info';

$lang->product->plans    = 'Plans';
$lang->product->releases = 'Releases';
$lang->product->docs     = 'Documents';
$lang->product->bugs     = 'Bugs';
$lang->product->projects = 'Projects';
$lang->product->cases    = 'Cases';
$lang->product->bulids   = 'Bulids';
$lang->product->roadmap  = 'Roadmap';
$lang->product->doc      = 'Doc';
$lang->product->project  = 'Projects';

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
$lang->product->QM        = 'Test manager';
$lang->product->RM        = 'Release manager';
$lang->product->acl       = 'Access limitation';
$lang->product->whitelist = 'Whitelist';

$lang->product->moduleStory  = 'By module';
$lang->product->searchStory  = 'Search';
$lang->product->assignedToMe = 'To me';
$lang->product->openedByMe   = 'Opened by me';
$lang->product->reviewedByMe = 'Reviewed by me';
$lang->product->closedByMe   = 'Closed by me';
$lang->product->draftStory   = 'Draft';
$lang->product->activeStory  = 'Active';
$lang->product->changedStory = 'Changed';
$lang->product->closedStory  = 'Closed';

$lang->product->allStory    = 'All';
$lang->product->allProduct  = 'All products';
$lang->product->allProductsOfProject = 'All related products';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Closed';

$lang->product->aclList['open']    = 'Default(Having product module prividge, can visit this product)';
$lang->product->aclList['private'] = 'Private(Only project team members can visit)';
$lang->product->aclList['custom']  = 'Whitelist(Project team members and who belongs to the whilelist groups can visit)';

$lang->product->storySummary = "Total <strong>%s</strong> stories in this page, estimate <strong>%s</strong> hours.";
