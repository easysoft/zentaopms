<?php
$config->product->dtable = new stdclass();

$config->product->dtable->fieldList['name']['name']         = 'name';
$config->product->dtable->fieldList['name']['title']        = $lang->product->name;
$config->product->dtable->fieldList['name']['minWidth']     = 248;
$config->product->dtable->fieldList['name']['type']         = 'link';
$config->product->dtable->fieldList['name']['flex']         = 1;
$config->product->dtable->fieldList['name']['nestedToggle'] = false;
$config->product->dtable->fieldList['name']['checkbox']     = true;
$config->product->dtable->fieldList['name']['sortType']     = true;
$config->product->dtable->fieldList['name']['align']        = 'left';
$config->product->dtable->fieldList['name']['border']       = 'right';
$config->product->dtable->fieldList['name']['link']         = helper::createLink('product', 'browse', "productID={id}");
$config->product->dtable->fieldList['name']['data-app']     = $app->tab;

$config->product->dtable->fieldList['productLine']['name']     = 'productLine';
$config->product->dtable->fieldList['productLine']['title']    = $lang->product->belongingLine;
$config->product->dtable->fieldList['productLine']['width']    = 136;
$config->product->dtable->fieldList['productLine']['type']     = 'format';
$config->product->dtable->fieldList['productLine']['sortType'] = false;
$config->product->dtable->fieldList['productLine']['border']   = 'right';
$config->product->dtable->fieldList['productLine']['align']    = 'left';

$config->product->dtable->fieldList['PO']['name']     = 'PO';
$config->product->dtable->fieldList['PO']['title']    = $lang->product->manager;
$config->product->dtable->fieldList['PO']['minWidth'] = 108;
$config->product->dtable->fieldList['PO']['type']     = 'avatarBtn';
$config->product->dtable->fieldList['PO']['sortType'] = true;
$config->product->dtable->fieldList['PO']['border']   = 'right';
$config->product->dtable->fieldList['PO']['align']    = 'left';

$config->product->dtable->fieldList['draftStories']['name']     = 'draftStories';
$config->product->dtable->fieldList['draftStories']['title']    = $lang->product->draftStory;
$config->product->dtable->fieldList['draftStories']['minWidth'] = 64;
$config->product->dtable->fieldList['draftStories']['type']     = 'number';
$config->product->dtable->fieldList['draftStories']['sortType'] = false;
$config->product->dtable->fieldList['draftStories']['align']    = 'center';

$config->product->dtable->fieldList['activeStories']['name']     = 'activeStories';
$config->product->dtable->fieldList['activeStories']['title']    = $lang->product->activeStory;
$config->product->dtable->fieldList['activeStories']['minWidth'] = 64;
$config->product->dtable->fieldList['activeStories']['type']     = 'number';
$config->product->dtable->fieldList['activeStories']['sortType'] = false;
$config->product->dtable->fieldList['activeStories']['align']    = 'center';

$config->product->dtable->fieldList['changingStories']['name']     = 'changingStories';
$config->product->dtable->fieldList['changingStories']['title']    = $lang->product->changingStory;
$config->product->dtable->fieldList['changingStories']['minWidth'] = 64;
$config->product->dtable->fieldList['changingStories']['type']     = 'number';
$config->product->dtable->fieldList['changingStories']['sortType'] = false;
$config->product->dtable->fieldList['changingStories']['align']    = 'center';

$config->product->dtable->fieldList['reviewingStories']['name']     = 'reviewingStories';
$config->product->dtable->fieldList['reviewingStories']['title']    = $lang->product->reviewingStory;
$config->product->dtable->fieldList['reviewingStories']['minWidth'] = 64;
$config->product->dtable->fieldList['reviewingStories']['type']     = 'number';
$config->product->dtable->fieldList['reviewingStories']['sortType'] = false;
$config->product->dtable->fieldList['reviewingStories']['align']    = 'center';

$config->product->dtable->fieldList['storyCompleteRate']['name']     = 'storyCompleteRate';
$config->product->dtable->fieldList['storyCompleteRate']['title']    = $lang->product->completeRate;
$config->product->dtable->fieldList['storyCompleteRate']['minWidth'] = 64;
$config->product->dtable->fieldList['storyCompleteRate']['type']     = 'progress';
$config->product->dtable->fieldList['storyCompleteRate']['sortType'] = false;
$config->product->dtable->fieldList['storyCompleteRate']['border']   = 'right';
$config->product->dtable->fieldList['storyCompleteRate']['align']    = 'center';

$config->product->dtable->fieldList['plans']['name']     = 'plans';
$config->product->dtable->fieldList['plans']['title']    = $lang->product->plan;
$config->product->dtable->fieldList['plans']['minWidth'] = 64;
$config->product->dtable->fieldList['plans']['type']     = 'number';
$config->product->dtable->fieldList['plans']['sortType'] = false;
$config->product->dtable->fieldList['plans']['border']   = 'right';
$config->product->dtable->fieldList['plans']['align']    = 'center';

$config->product->dtable->fieldList['status']['name']      = 'status';
$config->product->dtable->fieldList['status']['title']     = $lang->product->status;
$config->product->dtable->fieldList['status']['minWidth']  = 64;
$config->product->dtable->fieldList['status']['type']      = 'status';
$config->product->dtable->fieldList['status']['sortType']  = false;
$config->product->dtable->fieldList['status']['statusMap'] = $lang->product->statusList;
$config->product->dtable->fieldList['status']['border']    = 'right';
$config->product->dtable->fieldList['status']['align']     = 'center';

$config->product->dtable->fieldList['execution']['name']     = 'execution';
$config->product->dtable->fieldList['execution']['title']    = $lang->execution->common;
$config->product->dtable->fieldList['execution']['minWidth'] = 64;
$config->product->dtable->fieldList['execution']['type']     = 'number';
$config->product->dtable->fieldList['execution']['sortType'] = false;
$config->product->dtable->fieldList['execution']['border']   = 'right';
$config->product->dtable->fieldList['execution']['align']    = 'center';

$config->product->dtable->fieldList['testCaseCoverage']['name']     = 'testCaseCoverage';
$config->product->dtable->fieldList['testCaseCoverage']['title']    = $lang->product->testCaseCoverage;
$config->product->dtable->fieldList['testCaseCoverage']['minWidth'] = 86;
$config->product->dtable->fieldList['testCaseCoverage']['type']     = 'progress';
$config->product->dtable->fieldList['testCaseCoverage']['sortType'] = false;
$config->product->dtable->fieldList['testCaseCoverage']['border']   = 'right';

$config->product->dtable->fieldList['unresolvedBugs']['name']     = 'unresolvedBugs';
$config->product->dtable->fieldList['unresolvedBugs']['title']    = $lang->product->activatedBug;
$config->product->dtable->fieldList['unresolvedBugs']['minWidth'] = 64;
$config->product->dtable->fieldList['unresolvedBugs']['type']     = 'number';
$config->product->dtable->fieldList['unresolvedBugs']['sortType'] = false;
$config->product->dtable->fieldList['unresolvedBugs']['align']    = 'center';

$config->product->dtable->fieldList['bugFixedRate']['name']     = 'bugFixedRate';
$config->product->dtable->fieldList['bugFixedRate']['title']    = $lang->product->bugFixedRate;
$config->product->dtable->fieldList['bugFixedRate']['minWidth'] = 64;
$config->product->dtable->fieldList['bugFixedRate']['type']     = 'progress';
$config->product->dtable->fieldList['bugFixedRate']['sortType'] = false;
$config->product->dtable->fieldList['bugFixedRate']['align']    = 'center';
$config->product->dtable->fieldList['bugFixedRate']['border']   = 'right';

$config->product->dtable->fieldList['releases']['name']     = 'releases';
$config->product->dtable->fieldList['releases']['title']    = $lang->product->release;
$config->product->dtable->fieldList['releases']['minWidth'] = 64;
$config->product->dtable->fieldList['releases']['type']     = 'number';
$config->product->dtable->fieldList['releases']['sortType'] = false;
$config->product->dtable->fieldList['releases']['align']    = 'center';

$config->product->dtable->fieldList['actions']['name']     = 'actions';
$config->product->dtable->fieldList['actions']['title']    = $lang->actions;
$config->product->dtable->fieldList['actions']['type']     = 'actions';
$config->product->dtable->fieldList['actions']['minWidth'] = '60';
$config->product->dtable->fieldList['actions']['fixed']    = 'right';
$config->product->dtable->fieldList['actions']['menu']     = array('edit');
$config->product->dtable->fieldList['actions']['list']     = $config->product->actionList;

/* Default definition of WorkFlow extend fields. */
$config->product->dtable->extendField['name']     = 'field';
$config->product->dtable->extendField['title']    = 'name';
$config->product->dtable->extendField['minWidth'] = 64;
$config->product->dtable->extendField['type']     = 'number';
$config->product->dtable->extendField['sortType'] = false;
$config->product->dtable->extendField['align']    = 'center';
$config->product->dtable->extendField['border']   = 'left';

$config->productProject = new stdclass();
$config->productProject->showFields = array('id', 'program', 'name', 'status', 'PM', 'budget', 'begin', 'end', 'estimate', 'consume', 'progress');

/* Fields of product list page. */
if(!isset($config->product->all)) $config->product->all = new stdclass();
$config->product->all->dtable = new stdclass();

$config->product->all->dtable->fieldList['name']['name']         = 'name';
$config->product->all->dtable->fieldList['name']['shortTitle']   = $lang->product->name;
$config->product->all->dtable->fieldList['name']['width']        = 0.2;
$config->product->all->dtable->fieldList['name']['type']         = 'title';
$config->product->all->dtable->fieldList['name']['show']         = true;
$config->product->all->dtable->fieldList['name']['nestedToggle'] = false;
$config->product->all->dtable->fieldList['name']['checkbox']     = true;
$config->product->all->dtable->fieldList['name']['link']         = helper::createLink('product', 'browse', "productID={id}");
$config->product->all->dtable->fieldList['name']['sortType']     = true;
$config->product->all->dtable->fieldList['name']['group']        = 'g1';

$config->product->all->dtable->fieldList['productLine']['name']  = 'productLine';
$config->product->all->dtable->fieldList['productLine']['title'] = $lang->product->belongingLine;
$config->product->all->dtable->fieldList['productLine']['width'] = 136;
$config->product->all->dtable->fieldList['productLine']['type']  = 'format';
$config->product->all->dtable->fieldList['productLine']['show']  = true;
$config->product->all->dtable->fieldList['productLine']['group'] = 'g2';

$config->product->all->dtable->fieldList['PO']['name']     = 'PO';
$config->product->all->dtable->fieldList['PO']['title']    = $lang->product->manager;
$config->product->all->dtable->fieldList['PO']['width']    = 108;
$config->product->all->dtable->fieldList['PO']['type']     = 'avatarBtn';
$config->product->all->dtable->fieldList['PO']['show']     = true;
$config->product->all->dtable->fieldList['PO']['sortType'] = true;
$config->product->all->dtable->fieldList['PO']['group']    = 'g3';

$config->product->all->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->product->all->dtable->fieldList['createdBy']['title']    = $lang->openedByAB;
$config->product->all->dtable->fieldList['createdBy']['width']    = 80;
$config->product->all->dtable->fieldList['createdBy']['type']     = 'user';
$config->product->all->dtable->fieldList['createdBy']['sortType'] = true;
$config->product->all->dtable->fieldList['createdBy']['group']    = '1';

$config->product->all->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->product->all->dtable->fieldList['createdDate']['title']    = $lang->product->createdDate;
$config->product->all->dtable->fieldList['createdDate']['width']    = 90;
$config->product->all->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->product->all->dtable->fieldList['createdDate']['sortType'] = true;
$config->product->all->dtable->fieldList['createdDate']['group']    = '1';

$config->product->all->dtable->fieldList['draftStories']['name']     = 'draftStories';
$config->product->all->dtable->fieldList['draftStories']['title']    = $lang->product->draftStory;
$config->product->all->dtable->fieldList['draftStories']['width']    = 64;
$config->product->all->dtable->fieldList['draftStories']['type']     = 'number';
$config->product->all->dtable->fieldList['draftStories']['show']     = true;
$config->product->all->dtable->fieldList['draftStories']['sortType'] = false;
$config->product->all->dtable->fieldList['draftStories']['group']    = 'g4';

$config->product->all->dtable->fieldList['activeStories']['name']     = 'activeStories';
$config->product->all->dtable->fieldList['activeStories']['title']    = $lang->product->activeStory;
$config->product->all->dtable->fieldList['activeStories']['width']    = 64;
$config->product->all->dtable->fieldList['activeStories']['type']     = 'number';
$config->product->all->dtable->fieldList['activeStories']['show']     = true;
$config->product->all->dtable->fieldList['activeStories']['sortType'] = false;
$config->product->all->dtable->fieldList['activeStories']['group']    = 'g4';

$config->product->all->dtable->fieldList['changingStories']['name']     = 'changingStories';
$config->product->all->dtable->fieldList['changingStories']['title']    = $lang->product->changingStory;
$config->product->all->dtable->fieldList['changingStories']['width']    = 64;
$config->product->all->dtable->fieldList['changingStories']['type']     = 'number';
$config->product->all->dtable->fieldList['changingStories']['show']     = true;
$config->product->all->dtable->fieldList['changingStories']['sortType'] = false;
$config->product->all->dtable->fieldList['changingStories']['group']    = 'g4';

$config->product->all->dtable->fieldList['reviewingStories']['name']     = 'reviewingStories';
$config->product->all->dtable->fieldList['reviewingStories']['title']    = $lang->product->reviewingStory;
$config->product->all->dtable->fieldList['reviewingStories']['width']    = 64;
$config->product->all->dtable->fieldList['reviewingStories']['type']     = 'number';
$config->product->all->dtable->fieldList['reviewingStories']['show']     = true;
$config->product->all->dtable->fieldList['reviewingStories']['sortType'] = false;
$config->product->all->dtable->fieldList['reviewingStories']['group']    = 'g4';

$config->product->all->dtable->fieldList['totalStories']['name']     = 'totalStories';
$config->product->all->dtable->fieldList['totalStories']['title']    = $lang->product->totalStories;
$config->product->all->dtable->fieldList['totalStories']['width']    = 92;
$config->product->all->dtable->fieldList['totalStories']['minWidth'] = 100;
$config->product->all->dtable->fieldList['totalStories']['type']     = 'number';
$config->product->all->dtable->fieldList['totalStories']['sortType'] = false;
$config->product->all->dtable->fieldList['totalStories']['group']    = 'g4';

$config->product->all->dtable->fieldList['storyCompleteRate']['name']     = 'storyCompleteRate';
$config->product->all->dtable->fieldList['storyCompleteRate']['title']    = $lang->product->completeRate;
$config->product->all->dtable->fieldList['storyCompleteRate']['width']    = 64;
$config->product->all->dtable->fieldList['storyCompleteRate']['type']     = 'progress';
$config->product->all->dtable->fieldList['storyCompleteRate']['show']     = true;
$config->product->all->dtable->fieldList['storyCompleteRate']['sortType'] = false;
$config->product->all->dtable->fieldList['storyCompleteRate']['group']    = 'g4';

$config->product->all->dtable->fieldList['plans']['name']     = 'plans';
$config->product->all->dtable->fieldList['plans']['title']    = $lang->product->plan;
$config->product->all->dtable->fieldList['plans']['width']    = 64;
$config->product->all->dtable->fieldList['plans']['type']     = 'number';
$config->product->all->dtable->fieldList['plans']['show']     = true;
$config->product->all->dtable->fieldList['plans']['sortType'] = false;
$config->product->all->dtable->fieldList['plans']['group']    = 'g5';

$config->product->all->dtable->fieldList['executions']['name']     = 'executions';
$config->product->all->dtable->fieldList['executions']['title']    = $lang->execution->common;
$config->product->all->dtable->fieldList['executions']['width']    = 64;
$config->product->all->dtable->fieldList['executions']['type']     = 'number';
$config->product->all->dtable->fieldList['executions']['show']     = true;
$config->product->all->dtable->fieldList['executions']['sortType'] = false;
$config->product->all->dtable->fieldList['executions']['group']    = 'g6';

$config->product->all->dtable->fieldList['testCaseCoverage']['name']     = 'testCaseCoverage';
$config->product->all->dtable->fieldList['testCaseCoverage']['title']    = $lang->product->testCaseCoverage;
$config->product->all->dtable->fieldList['testCaseCoverage']['width']    = 80;
$config->product->all->dtable->fieldList['testCaseCoverage']['type']     = 'progress';
$config->product->all->dtable->fieldList['testCaseCoverage']['minWidth'] = 80;
$config->product->all->dtable->fieldList['testCaseCoverage']['show']     = true;
$config->product->all->dtable->fieldList['testCaseCoverage']['sortType'] = false;
$config->product->all->dtable->fieldList['testCaseCoverage']['group']    = 'g7';

$config->product->all->dtable->fieldList['unresolvedBugs']['name']     = 'unresolvedBugs';
$config->product->all->dtable->fieldList['unresolvedBugs']['title']    = $lang->product->activatedBug;
$config->product->all->dtable->fieldList['unresolvedBugs']['width']    = 64;
$config->product->all->dtable->fieldList['unresolvedBugs']['minWidth'] = 86;
$config->product->all->dtable->fieldList['unresolvedBugs']['type']     = 'number';
$config->product->all->dtable->fieldList['unresolvedBugs']['show']     = true;
$config->product->all->dtable->fieldList['unresolvedBugs']['sortType'] = false;
$config->product->all->dtable->fieldList['unresolvedBugs']['group']    = 'g8';

$config->product->all->dtable->fieldList['totalBugs']['name']     = 'totalBugs';
$config->product->all->dtable->fieldList['totalBugs']['title']    = $lang->product->totalBugs;
$config->product->all->dtable->fieldList['totalBugs']['width']    = 64;
$config->product->all->dtable->fieldList['totalBugs']['minWidth'] = 86;
$config->product->all->dtable->fieldList['totalBugs']['type']     = 'number';
$config->product->all->dtable->fieldList['totalBugs']['sortType'] = false;
$config->product->all->dtable->fieldList['totalBugs']['group']    = 'g8';

$config->product->all->dtable->fieldList['bugFixedRate']['name']     = 'bugFixedRate';
$config->product->all->dtable->fieldList['bugFixedRate']['title']    = $lang->product->bugFixedRate;
$config->product->all->dtable->fieldList['bugFixedRate']['width']    = 64;
$config->product->all->dtable->fieldList['bugFixedRate']['type']     = 'progress';
$config->product->all->dtable->fieldList['bugFixedRate']['show']     = true;
$config->product->all->dtable->fieldList['bugFixedRate']['sortType'] = false;
$config->product->all->dtable->fieldList['bugFixedRate']['group']    = 'g8';

$config->product->all->dtable->fieldList['releases']['name']     = 'releases';
$config->product->all->dtable->fieldList['releases']['title']    = $lang->product->release;
$config->product->all->dtable->fieldList['releases']['width']    = 80;
$config->product->all->dtable->fieldList['releases']['type']     = 'number';
$config->product->all->dtable->fieldList['releases']['show']     = true;
$config->product->all->dtable->fieldList['releases']['sortType'] = false;
$config->product->all->dtable->fieldList['releases']['group']    = 'g9';

$config->product->all->dtable->fieldList['latestReleaseDate']['name']     = 'latestReleaseDate';
$config->product->all->dtable->fieldList['latestReleaseDate']['title']    = $lang->product->latestReleaseDate;
$config->product->all->dtable->fieldList['latestReleaseDate']['width']    = 96;
$config->product->all->dtable->fieldList['latestReleaseDate']['minWidth'] = 120;
$config->product->all->dtable->fieldList['latestReleaseDate']['type']     = 'date';
$config->product->all->dtable->fieldList['latestReleaseDate']['sortType'] = false;
$config->product->all->dtable->fieldList['latestReleaseDate']['group']    = 'g9';

$config->product->all->dtable->fieldList['latestRelease']['name']       = 'latestRelease';
$config->product->all->dtable->fieldList['latestRelease']['title']      = $lang->product->latestRelease;
$config->product->all->dtable->fieldList['latestRelease']['width']      = 136;
$config->product->all->dtable->fieldList['latestRelease']['minWidth']   = 80;
$config->product->all->dtable->fieldList['latestRelease']['type']       = 'text';
$config->product->all->dtable->fieldList['latestRelease']['filterType'] = true;
$config->product->all->dtable->fieldList['latestRelease']['group']      = 'g9';

if($config->systemMode != 'ALM' && $config->systemMode != 'PLM')
{
    unset($config->product->dtable->fieldList['productLine']);
    unset($config->product->all->dtable->fieldList['productLine']);
}
