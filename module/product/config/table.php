<?php
$config->product->dtable = new stdclass();

$config->product->dtable->fieldList['name']['name']         = 'name';
$config->product->dtable->fieldList['name']['title']        = $lang->product->name;
$config->product->dtable->fieldList['name']['minWidth']     = 212;
$config->product->dtable->fieldList['name']['fixed']        = 'left';
$config->product->dtable->fieldList['name']['type']         = 'link';
$config->product->dtable->fieldList['name']['flex']         = 1;
$config->product->dtable->fieldList['name']['nestedToggle'] = false;
$config->product->dtable->fieldList['name']['checkbox']     = true;
$config->product->dtable->fieldList['name']['iconRender']   = true;
$config->product->dtable->fieldList['name']['sortType']     = true;
$config->product->dtable->fieldList['name']['iconRender']   = 'RAWJS<function(row){return row.data.type === \'program\' ? \'icon-cards-view text-gray\' : \'\'}>RAWJS';
$config->product->dtable->fieldList['name']['align']        = 'left';

$config->product->dtable->fieldList['productLine']['name']     = 'productLine';
$config->product->dtable->fieldList['productLine']['title']    = $lang->product->belongingLine;
$config->product->dtable->fieldList['productLine']['minWidth'] = 114;
$config->product->dtable->fieldList['productLine']['type']     = 'format';
$config->product->dtable->fieldList['productLine']['sortType'] = true;
$config->product->dtable->fieldList['productLine']['group']    = $lang->SRCommon;
$config->product->dtable->fieldList['productLine']['border']   = 'right';
$config->product->dtable->fieldList['productLine']['align']    = 'left';
$config->product->dtable->fieldList['productLine']['flex']     = 1;

$config->product->dtable->fieldList['PO']['name']     = 'PO';
$config->product->dtable->fieldList['PO']['title']    = $lang->product->manager;
$config->product->dtable->fieldList['PO']['minWidth'] = 104;
$config->product->dtable->fieldList['PO']['type']     = 'avatarBtn';
$config->product->dtable->fieldList['PO']['sortType'] = false;
$config->product->dtable->fieldList['PO']['border']   = 'right';
$config->product->dtable->fieldList['PO']['align']    = 'left';

$config->product->dtable->fieldList['feedback']['name']     = 'feedback';
$config->product->dtable->fieldList['feedback']['title']    = $lang->product->feedback;
$config->product->dtable->fieldList['feedback']['minWidth'] = 62;
$config->product->dtable->fieldList['feedback']['type']     = 'format';
$config->product->dtable->fieldList['feedback']['sortType'] = false;
$config->product->dtable->fieldList['feedback']['group']    = $lang->SRCommon;
$config->product->dtable->fieldList['feedback']['border']   = 'right';
$config->product->dtable->fieldList['feedback']['align']    = 'center';

$config->product->dtable->fieldList['draftStories']['name']     = 'draftStories';
$config->product->dtable->fieldList['draftStories']['title']    = $lang->product->draftStory;
$config->product->dtable->fieldList['draftStories']['minWidth'] = 82;
$config->product->dtable->fieldList['draftStories']['type']     = 'format';
$config->product->dtable->fieldList['draftStories']['sortType'] = false;
$config->product->dtable->fieldList['draftStories']['group']    = $lang->SRCommon;
$config->product->dtable->fieldList['draftStories']['align']    = 'center';

$config->product->dtable->fieldList['activeStories']['name']     = 'activeStories';
$config->product->dtable->fieldList['activeStories']['title']    = $lang->product->activeStory;
$config->product->dtable->fieldList['activeStories']['minWidth'] = 62;
$config->product->dtable->fieldList['activeStories']['type']     = 'format';
$config->product->dtable->fieldList['activeStories']['sortType'] = false;
$config->product->dtable->fieldList['activeStories']['group']    = $lang->SRCommon;
$config->product->dtable->fieldList['activeStories']['align']    = 'center';

$config->product->dtable->fieldList['changingStories']['name']     = 'changingStories';
$config->product->dtable->fieldList['changingStories']['title']    = $lang->product->changingStory;
$config->product->dtable->fieldList['changingStories']['minWidth'] = 62;
$config->product->dtable->fieldList['changingStories']['type']     = 'format';
$config->product->dtable->fieldList['changingStories']['sortType'] = false;
$config->product->dtable->fieldList['changingStories']['group']    = $lang->SRCommon;
$config->product->dtable->fieldList['changingStories']['align']    = 'center';

$config->product->dtable->fieldList['reviewingStories']['name']     = 'reviewingStories';
$config->product->dtable->fieldList['reviewingStories']['title']    = $lang->product->reviewingStory;
$config->product->dtable->fieldList['reviewingStories']['minWidth'] = 62;
$config->product->dtable->fieldList['reviewingStories']['type']     = 'format';
$config->product->dtable->fieldList['reviewingStories']['sortType'] = false;
$config->product->dtable->fieldList['reviewingStories']['group']    = $lang->SRCommon;
$config->product->dtable->fieldList['reviewingStories']['align']    = 'center';

$config->product->dtable->fieldList['storyCompleteRate']['name']     = 'storyCompleteRate';
$config->product->dtable->fieldList['storyCompleteRate']['title']    = $lang->product->storyCompleteRate;
$config->product->dtable->fieldList['storyCompleteRate']['minWidth'] = 62;
$config->product->dtable->fieldList['storyCompleteRate']['type']     = 'circleProgress';
$config->product->dtable->fieldList['storyCompleteRate']['sortType'] = false;
$config->product->dtable->fieldList['storyCompleteRate']['group']    = $lang->SRCommon;
$config->product->dtable->fieldList['storyCompleteRate']['border']   = 'right';

$config->product->dtable->fieldList['plans']['name']     = 'plans';
$config->product->dtable->fieldList['plans']['title']    = $lang->product->plan;
$config->product->dtable->fieldList['plans']['minWidth'] = 66;
$config->product->dtable->fieldList['plans']['type']     = 'format';
$config->product->dtable->fieldList['plans']['sortType'] = false;
$config->product->dtable->fieldList['plans']['border']   = 'right';
$config->product->dtable->fieldList['plans']['align']    = 'center';

$config->product->dtable->fieldList['execution']['name']     = 'execution';
$config->product->dtable->fieldList['execution']['title']    = $lang->execution->common;
$config->product->dtable->fieldList['execution']['minWidth'] = 66;
$config->product->dtable->fieldList['execution']['type']     = 'format';
$config->product->dtable->fieldList['execution']['sortType'] = false;
$config->product->dtable->fieldList['execution']['border']   = 'right';
$config->product->dtable->fieldList['execution']['align']    = 'center';

$config->product->dtable->fieldList['testCaseCoverage']['name']     = 'testCaseCoverage';
$config->product->dtable->fieldList['testCaseCoverage']['title']    = $lang->product->testCaseCoverage;
$config->product->dtable->fieldList['testCaseCoverage']['minWidth'] = 86;
$config->product->dtable->fieldList['testCaseCoverage']['type']     = 'circleProgress';
$config->product->dtable->fieldList['testCaseCoverage']['sortType'] = false;
$config->product->dtable->fieldList['testCaseCoverage']['border']   = 'right';

$config->product->dtable->fieldList['unResolvedBugs']['name']     = 'unResolvedBugs';
$config->product->dtable->fieldList['unResolvedBugs']['title']    = $lang->product->activatedBug;
$config->product->dtable->fieldList['unResolvedBugs']['minWidth'] = 62;
$config->product->dtable->fieldList['unResolvedBugs']['type']     = 'format';
$config->product->dtable->fieldList['unResolvedBugs']['sortType'] = false;
$config->product->dtable->fieldList['unResolvedBugs']['group']    = 'Bug';
$config->product->dtable->fieldList['unResolvedBugs']['align']    = 'center';

$config->product->dtable->fieldList['bugFixedRate']['name']     = 'bugFixedRate';
$config->product->dtable->fieldList['bugFixedRate']['title']    = $lang->product->bugFixedRate;
$config->product->dtable->fieldList['bugFixedRate']['minWidth'] = 62;
$config->product->dtable->fieldList['bugFixedRate']['type']     = 'circleProgress';
$config->product->dtable->fieldList['bugFixedRate']['sortType'] = false;
$config->product->dtable->fieldList['bugFixedRate']['group']    = 'Bug';
$config->product->dtable->fieldList['bugFixedRate']['border']   = 'right';

$config->product->dtable->fieldList['releases']['name']     = 'releases';
$config->product->dtable->fieldList['releases']['title']    = $lang->product->release;
$config->product->dtable->fieldList['releases']['minWidth'] = 68;
$config->product->dtable->fieldList['releases']['type']     = 'format';
$config->product->dtable->fieldList['releases']['sortType'] = false;
$config->product->dtable->fieldList['releases']['align']    = 'center';
