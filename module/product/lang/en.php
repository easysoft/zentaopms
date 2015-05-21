<?php
/**
 * The product module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: en.php 5091 2013-07-10 06:06:46Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->product->common      = $lang->productcommon;
$lang->product->index       = "Index";
$lang->product->browse      = "Browse";
$lang->product->dynamic     = "Dynamic";
$lang->product->view        = "Info";
$lang->product->edit        = "Edit";
$lang->product->batchEdit   = "Batch Edit";
$lang->product->create      = "Create";
$lang->product->read        = "Info";
$lang->product->delete      = "Delete";
$lang->product->deleted     = "Deleted";
$lang->product->close       = "Close";
$lang->product->select      = "--select {$lang->productcommon}--";
$lang->product->mine        = 'I charge : ';
$lang->product->other       = 'Other : ';
$lang->product->closed      = 'Closed';
$lang->product->updateOrder = 'Order';

$lang->product->basicInfo = 'Basic info';
$lang->product->otherInfo = 'Other info';

$lang->product->plans    = 'Plans';
$lang->product->releases = 'Releases';
$lang->product->docs     = 'Documents';
$lang->product->bugs     = 'Bugs';
$lang->product->projects = "{$lang->projectcommon}s";
$lang->product->cases    = 'Cases';
$lang->product->builds   = 'Builds';
$lang->product->roadmap  = 'Roadmap';
$lang->product->doc      = 'Doc';
$lang->product->project  = "{$lang->projectcommon}s";

$lang->product->selectProduct   = "Select {$lang->productcommon}";
$lang->product->saveButton      = " Save (S) ";
$lang->product->confirmDelete   = " Are you sure to delete this {$lang->productcommon}?";
$lang->product->ajaxGetProjects = "API: {$lang->projectcommon}s of {$lang->productcommon}";
$lang->product->ajaxGetPlans    = "API: plans of {$lang->productcommon}";

$lang->product->errorFormat    = 'Error format.';
$lang->product->errorEmptyName = 'Name can not be empty.';
$lang->product->errorEmptyCode = 'Code can not be empty';
$lang->product->errorNoProduct = "No {$lang->productcommon} in system yet.";
$lang->product->accessDenied   = "Access to this {$lang->productcommon} denined.";

$lang->product->id        = 'ID';
$lang->product->company   = 'Company';
$lang->product->name      = 'Name';
$lang->product->code      = 'Code';
$lang->product->order     = 'Order';
$lang->product->status    = 'Status';
$lang->product->desc      = 'Desc';
$lang->product->PO        = "{$lang->productcommon} owner";
$lang->product->QD        = 'Quality director';
$lang->product->RD        = 'Release director';
$lang->product->acl       = 'Access limitation';
$lang->product->whitelist = 'Whitelist';

$lang->product->moduleStory  = 'Module';
$lang->product->searchStory  = 'Search';
$lang->product->assignedToMe = 'To me';
$lang->product->openedByMe   = 'My opened';
$lang->product->reviewedByMe = 'My reviewed';
$lang->product->closedByMe   = 'My closed';
$lang->product->draftStory   = 'Draft';
$lang->product->activeStory  = 'Active';
$lang->product->changedStory = 'Changed';
$lang->product->willClose    = 'Will close';
$lang->product->closedStory  = 'Closed';
$lang->product->unclosed     = 'Unclosed';

$lang->product->allStory    = 'All';
$lang->product->allProduct  = "All {$lang->productcommon}s";
$lang->product->allProductsOfProject = "All related {$lang->productcommon}s";

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Closed';

$lang->product->aclList['open']    = "Default(Having {$lang->productcommon} module prividge, can visit this {$lang->productcommon})";
$lang->product->aclList['private'] = "Private(Only {$lang->projectcommon} team members can visit)";
$lang->product->aclList['custom']  = "Whitelist({$lang->projectcommon} team members and who belongs to the whilelist groups can visit)";

$lang->product->storySummary = "Total <strong>%s</strong> stories in this page, estimate <strong>%s</strong> hours, case coverage is %s ";
$lang->product->noMatched    = "No matched {$lang->productcommon} including '%s'";
