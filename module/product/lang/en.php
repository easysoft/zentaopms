<?php
/**
 * The product module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: en.php 5091 2013-07-10 06:06:46Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->product->common      = $lang->productCommon;
$lang->product->index       = $lang->productCommon . "Home";
$lang->product->browse      = "Story List";
$lang->product->dynamic     = "Dynamic";
$lang->product->view        = "Overview";
$lang->product->edit        = "Edit {$lang->productCommon}";
$lang->product->batchEdit   = "Batch Edit";
$lang->product->create      = "Create {$lang->productCommon}";
$lang->product->delete      = "Delete {$lang->productCommon}";
$lang->product->deleted     = "Deleted";
$lang->product->close       = "Close";
$lang->product->select      = "    Select {$lang->productCommon}";
$lang->product->mine        = 'My responsibility:';
$lang->product->other       = 'Other:';
$lang->product->closed      = 'Closed';
$lang->product->updateOrder = 'Ranking';
$lang->product->all         = "All {$lang->productCommon}";
$lang->product->export      = "Export";

$lang->product->basicInfo = 'Basic Info';
$lang->product->otherInfo = 'Other Info';

$lang->product->plans    = 'Plan';
$lang->product->releases = 'Release';
$lang->product->docs     = 'Doc';
$lang->product->bugs     = 'Linked Bug';
$lang->product->projects = "Linked {$lang->projectCommon}";
$lang->product->cases    = 'Case';
$lang->product->builds   = 'Build';
$lang->product->roadmap  = 'Roadmap';
$lang->product->doc      = 'Doc';
$lang->product->project  = $lang->projectCommon . 'List';
$lang->product->build    = 'Build';

$lang->product->activeStories    = 'Activated Story';
$lang->product->changedStories   = 'Changed Story';
$lang->product->draftStories     = 'Draft Story';
$lang->product->closedStories    = 'Closed Story';
$lang->product->unResolvedBugs   = 'Unresolved Bug';
$lang->product->assignToNullBugs = 'Unassigned Bug';

$lang->product->confirmDelete   = " Do you want to delete {$lang->productCommon}?";

$lang->product->errorNoProduct = "{$lang->productCommon} is not created yet!";
$lang->product->accessDenied   = "You have no access to {$lang->productCommon}.";

$lang->product->id        = 'ID';
$lang->product->name      = "Name";
$lang->product->code      = "Alias";
$lang->product->line      = 'Product Line';
$lang->product->order     = 'Sort';
$lang->product->type      = "Type";
$lang->product->status    = 'Status';
$lang->product->desc      = 'Description';
$lang->product->PO        = "PO";
$lang->product->QD        = 'QA Manager';
$lang->product->RD        = 'Release Manager';
$lang->product->acl       = 'Access Control';
$lang->product->whitelist = 'Whitelist';
$lang->product->branch    = '%s';

$lang->product->searchStory  = 'Search';
$lang->product->assignedToMe = 'Assigned To Me';
$lang->product->openedByMe   = 'Created By Me';
$lang->product->reviewedByMe = 'Reviewed By Me';
$lang->product->closedByMe   = 'ClosedByMe';
$lang->product->draftStory   = 'Draft';
$lang->product->activeStory  = 'Activated';
$lang->product->changedStory = 'Changed';
$lang->product->willClose    = 'ToBeClose';
$lang->product->closedStory  = 'Closed';
$lang->product->unclosed     = 'Open';
$lang->product->unplan       = 'Wait';
$lang->product->my           = 'My';

$lang->product->allStory    = 'All';
$lang->product->allProduct  = 'All' . $lang->productCommon;
$lang->product->allProductsOfProject = 'All linked' . $lang->productCommon;

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = 'Normal';
$lang->product->typeList['branch']   = 'Multi Branch';
$lang->product->typeList['platform'] = 'Multi Platform';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = '(Divide the custom content.)';
$lang->product->typeTips['platform'] = '(Divide IOS, Android, PC, etc.)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = 'Branch';
$lang->product->branchName['platform'] = 'Platform';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Closed';

$lang->product->aclList['open']    = "Default (User with {$lang->productCommon} View privilege have access to it)";
$lang->product->aclList['private'] = "Private {$lang->productCommon} ({$lang->projectCommon} team members only)";
$lang->product->aclList['custom']  = 'Custom (Team members and Whitelist members have access to it.)';

$lang->product->storySummary   = " <strong>%s</strong> Story(ies), <strong>%s</strong> hour(s) estimated, case coverage is <strong>%s</strong> on this page.";
$lang->product->checkedSummary = " <strong>%total%</strong> Checked, <strong>%estimate%</strong> hour(s) estimated, case coverage is <strong>%rate%</strong>.";
$lang->product->noMatched      = '"%s" cannot be found.' . $lang->productCommon;

$lang->product->mySelects['assignedtome'] = $lang->product->assignedToMe;
$lang->product->mySelects['openedbyme']   = $lang->product->openedByMe;
$lang->product->mySelects['resolvedbyme'] = $lang->product->reviewedByMe;

$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['unplan']       = $lang->product->unplan;
$lang->product->featureBar['browse']['allstory']     = $lang->product->allStory;



$lang->product->featureBar['browse']['my']           = $lang->product->my;
$lang->product->featureBar['browse']['closedbyme']   = $lang->product->closedByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['activestory']  = $lang->product->activeStory;
$lang->product->featureBar['browse']['changedstory'] = $lang->product->changedStory;
$lang->product->featureBar['browse']['willclose']    = $lang->product->willClose;
$lang->product->featureBar['browse']['closedstory']  = $lang->product->closedStory;
