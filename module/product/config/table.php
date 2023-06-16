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

$config->product->dtable->fieldList['productLine']['name']     = 'productLine';
$config->product->dtable->fieldList['productLine']['title']    = $lang->product->belongingLine;
$config->product->dtable->fieldList['productLine']['width']    = 136;
$config->product->dtable->fieldList['productLine']['type']     = 'format';
$config->product->dtable->fieldList['productLine']['sortType'] = true;
$config->product->dtable->fieldList['productLine']['border']   = 'right';
$config->product->dtable->fieldList['productLine']['align']    = 'left';

$config->product->dtable->fieldList['PO']['name']     = 'PO';
$config->product->dtable->fieldList['PO']['title']    = $lang->product->manager;
$config->product->dtable->fieldList['PO']['minWidth'] = 108;
$config->product->dtable->fieldList['PO']['type']     = 'avatarBtn';
$config->product->dtable->fieldList['PO']['sortType'] = false;
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

$config->product->dtable->fieldList['unResolvedBugs']['name']     = 'unResolvedBugs';
$config->product->dtable->fieldList['unResolvedBugs']['title']    = $lang->product->activatedBug;
$config->product->dtable->fieldList['unResolvedBugs']['minWidth'] = 64;
$config->product->dtable->fieldList['unResolvedBugs']['type']     = 'number';
$config->product->dtable->fieldList['unResolvedBugs']['sortType'] = false;
$config->product->dtable->fieldList['unResolvedBugs']['align']    = 'center';

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
