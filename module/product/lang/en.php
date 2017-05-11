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
$lang->product->common      = $lang->productCommon . 'View';
$lang->product->index       = $lang->productCommon . "Home";
$lang->product->browse      = "{$lang->productCommon}s";
$lang->product->dynamic     = "Dynamic";
$lang->product->view        = "{$lang->productCommon}s";
$lang->product->edit        = "Edit {$lang->productCommon}";
$lang->product->batchEdit   = "Batch Edit";
$lang->product->create      = "Create {$lang->productCommon}";
$lang->product->delete      = "Delete {$lang->productCommon}";
$lang->product->deleted     = "Deleted";
$lang->product->close       = "Close";
$lang->product->select      = "	Select {$lang->productCommon}";
$lang->product->mine        = 'My responsibility:';
$lang->product->other       = 'Other:';
$lang->product->closed      = 'Closed';
$lang->product->updateOrder = 'Ranking';
$lang->product->all         = "All {$lang->productCommon}";

$lang->product->basicInfo = 'Basic Info';
$lang->product->otherInfo = 'Other Info';

$lang->product->plans    = 'Plans';
$lang->product->releases = 'Releases';
$lang->product->docs     = 'Documents';
$lang->product->bugs     = 'Linked Bug';
$lang->product->projects = "Linked {$lang->projectCommon}s";
$lang->product->cases    = 'Cases';
$lang->product->builds   = 'Builds';
$lang->product->roadmap  = 'Roadmap';
$lang->product->doc      = 'Documents';
$lang->product->project  = $lang->projectCommon . 'List';
$lang->product->build    = 'Build List';

$lang->product->confirmDelete   = " Do you want to delete {$lang->productCommon}?";

$lang->product->errorNoProduct = "{$lang->productCommon} is not created yet!";
$lang->product->accessDenied   = "You have no access to {$lang->productCommon}.";

$lang->product->name      = "{$lang->productCommon} Name";
$lang->product->code      = "{$lang->productCommon} Code";
$lang->product->order     = 'Sort';
$lang->product->type      = "{$lang->productCommon} Type";
$lang->product->status    = 'Status';
$lang->product->desc      = 'Description';
$lang->product->PO        = "{$lang->productCommon} owner";
$lang->product->QD        = 'Quality Director';
$lang->product->RD        = 'Release Director';
$lang->product->acl       = 'Acceptance';
$lang->product->whitelist = 'Whitelist';
$lang->product->branch    = '%s';

$lang->product->searchStory  = 'Search';
$lang->product->assignedToMe = 'Assigned to Me';
$lang->product->openedByMe   = 'Created by Me';
$lang->product->reviewedByMe = 'Reviewed by Me';
$lang->product->closedByMe   = 'Closed by Me';
$lang->product->draftStory   = 'Draft';
$lang->product->activeStory  = 'Activate';
$lang->product->changedStory = 'Changed';
$lang->product->willClose    = 'To be Closed';
$lang->product->closedStory  = 'Closed';
$lang->product->unclosed     = 'Open';

$lang->product->allStory    = 'All Stories';
$lang->product->allProduct  = 'All' . $lang->productCommon;
$lang->product->allProductsOfProject = 'All linked' . $lang->productCommon;

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = 'Normal';
$lang->product->typeList['branch']   = 'Multi Branch';
$lang->product->typeList['platform'] = 'Multi Platform';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = '(Generally used to divide the custom content of different users)';
$lang->product->typeTips['platform'] = '(Generally used to divide ios, Andrews, pc and so on)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = 'Branch';
$lang->product->branchName['platform'] = 'Platform';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Closed';

$lang->product->aclList['open']    = "Default({$lang->productCommon} with View permission can access to it)";
$lang->product->aclList['private'] = "Private{$lang->productCommon}({$lang->projectCommon}team members only)";
$lang->product->aclList['custom']  = 'Custom(Team members and Whitelisr members have access to it.)';

$lang->product->storySummary = " <strong>%s</strong> Story in total on this page, <strong>%s</strong> man-hour estimated. Case coverage is <strong>%s</strong>.";
$lang->product->noMatched    = '"%s" cannot be found.' . $lang->productCommon;

$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['allstory']     = $lang->product->allStory;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['openedbyme']   = $lang->product->openedByMe;
$lang->product->featureBar['browse']['reviewedbyme'] = $lang->product->reviewedByMe;
$lang->product->featureBar['browse']['closedbyme']   = $lang->product->closedByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['activestory']  = $lang->product->activeStory;
$lang->product->featureBar['browse']['changedstory'] = $lang->product->changedStory;
$lang->product->featureBar['browse']['willclose']    = $lang->product->willClose;
$lang->product->featureBar['browse']['closedstory']  = $lang->product->closedStory;
