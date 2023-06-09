<?php
global $lang;
$config->testcase->dtable = new stdclass();
$config->testcase->dtable->fieldList['title']['name']         = 'title';
$config->testcase->dtable->fieldList['title']['title']        = $lang->testcase->title;
$config->testcase->dtable->fieldList['title']['type']         = 'title';
$config->testcase->dtable->fieldList['title']['fixed']        = 'left';
$config->testcase->dtable->fieldList['title']['checkbox']     = true;
$config->testcase->dtable->fieldList['title']['nestedToggle'] = true;
$config->testcase->dtable->fieldList['title']['iconRender']   = 'RAWJS<function(val,row){ if(row.data.isCase == 2) return \'icon-folder-open-o text-gray\'; return \'\';}>RAWJS';
$config->testcase->dtable->fieldList['title']['group']        = '1';
if(common::hasPriv('testcase', 'view')) $config->testcase->dtable->fieldList['title']['link'] = helper::createLink('testcase', 'view', "caseID={id}");

$config->testcase->dtable->fieldList['pri']['name']  = 'pri';
$config->testcase->dtable->fieldList['pri']['title'] = $lang->testcase->pri;
$config->testcase->dtable->fieldList['pri']['type']  = 'pri';
$config->testcase->dtable->fieldList['pri']['group'] = '2';

$config->testcase->dtable->fieldList['type']['name']  = 'type';
$config->testcase->dtable->fieldList['type']['title'] = $lang->testcase->type;
$config->testcase->dtable->fieldList['type']['type']  = 'category';
$config->testcase->dtable->fieldList['type']['map']   = $lang->testcase->typeList;
$config->testcase->dtable->fieldList['type']['group'] = '2';

$config->testcase->dtable->fieldList['status']['name']      = 'status';
$config->testcase->dtable->fieldList['status']['title']     = $lang->testcase->status;
$config->testcase->dtable->fieldList['status']['type']      = 'status';
$config->testcase->dtable->fieldList['status']['statusMap'] = $lang->testcase->statusList;
$config->testcase->dtable->fieldList['status']['group']     = '2';

$config->testcase->dtable->fieldList['stage']['name']  = 'stage';
$config->testcase->dtable->fieldList['stage']['title'] = $lang->testcase->stage;
$config->testcase->dtable->fieldList['stage']['type']  = 'text';
$config->testcase->dtable->fieldList['stage']['group'] = '2';

$config->testcase->dtable->fieldList['precondition']['name']  = 'precondition';
$config->testcase->dtable->fieldList['precondition']['title'] = $lang->testcase->precondition;
$config->testcase->dtable->fieldList['precondition']['type']  = 'desc';
$config->testcase->dtable->fieldList['precondition']['group'] = '3';

$config->testcase->dtable->fieldList['story']['name']  = 'story';
$config->testcase->dtable->fieldList['story']['title'] = $lang->testcase->story;
$config->testcase->dtable->fieldList['story']['type']  = 'desc';
$config->testcase->dtable->fieldList['story']['group'] = '3';
if(common::hasPriv('story', 'view')) $config->testcase->dtable->fieldList['story']['link']  = helper::createLink('story', 'view', "storyID={story}");

$config->testcase->dtable->fieldList['keywords']['name']  = 'keywords';
$config->testcase->dtable->fieldList['keywords']['title'] = $lang->testcase->keywords;
$config->testcase->dtable->fieldList['keywords']['type']  = 'text';
$config->testcase->dtable->fieldList['keywords']['group'] = '3';

$config->testcase->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->testcase->dtable->fieldList['openedBy']['title'] = $lang->testcase->openedByAB;
$config->testcase->dtable->fieldList['openedBy']['type']  = 'user';
$config->testcase->dtable->fieldList['openedBy']['group'] = '4';

$config->testcase->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->testcase->dtable->fieldList['openedDate']['title'] = $lang->testcase->openedDate;
$config->testcase->dtable->fieldList['openedDate']['type']  = 'date';
$config->testcase->dtable->fieldList['openedDate']['group'] = '4';

$config->testcase->dtable->fieldList['reviewedBy']['name']  = 'reviewedBy';
$config->testcase->dtable->fieldList['reviewedBy']['title'] = $lang->testcase->reviewedByAB;
$config->testcase->dtable->fieldList['reviewedBy']['type']  = 'user';
$config->testcase->dtable->fieldList['reviewedBy']['group'] = '4';

$config->testcase->dtable->fieldList['reviewedDate']['name']  = 'reviewedDate';
$config->testcase->dtable->fieldList['reviewedDate']['title'] = $lang->testcase->reviewedDate;
$config->testcase->dtable->fieldList['reviewedDate']['type']  = 'time';
$config->testcase->dtable->fieldList['reviewedDate']['group'] = '4';

$config->testcase->dtable->fieldList['lastRunner']['name']  = 'lastRunner';
$config->testcase->dtable->fieldList['lastRunner']['title'] = $lang->testcase->lastRunner;
$config->testcase->dtable->fieldList['lastRunner']['type']  = 'user';
$config->testcase->dtable->fieldList['lastRunner']['group'] = '4';

$config->testcase->dtable->fieldList['lastRunDate']['name']  = 'lastRunDate';
$config->testcase->dtable->fieldList['lastRunDate']['title'] = $lang->testcase->lastRunDate;
$config->testcase->dtable->fieldList['lastRunDate']['type']  = 'time';
$config->testcase->dtable->fieldList['lastRunDate']['group'] = '4';

$config->testcase->dtable->fieldList['lastRunResult']['name']      = 'lastRunResult';
$config->testcase->dtable->fieldList['lastRunResult']['title']     = $lang->testcase->lastRunResult;
$config->testcase->dtable->fieldList['lastRunResult']['type']      = 'status';
$config->testcase->dtable->fieldList['lastRunResult']['statusMap'] = $lang->testcase->resultList;
$config->testcase->dtable->fieldList['lastRunResult']['group']     = '4';

$config->testcase->dtable->fieldList['bugs']['name']  = 'bugs';
$config->testcase->dtable->fieldList['bugs']['title'] = $lang->testcase->bugsAB;
$config->testcase->dtable->fieldList['bugs']['type']  = 'number';
$config->testcase->dtable->fieldList['bugs']['group'] = '5';

$config->testcase->dtable->fieldList['results']['name']  = 'results';
$config->testcase->dtable->fieldList['results']['title'] = $lang->testcase->resultsAB;
$config->testcase->dtable->fieldList['results']['type']  = 'number';
$config->testcase->dtable->fieldList['results']['group'] = '5';

$config->testcase->dtable->fieldList['stepNumber']['name']  = 'stepNumber';
$config->testcase->dtable->fieldList['stepNumber']['title'] = $lang->testcase->stepNumberAB;
$config->testcase->dtable->fieldList['stepNumber']['type']  = 'number';
$config->testcase->dtable->fieldList['stepNumber']['group'] = '5';

$config->testcase->dtable->fieldList['version']['name']  = 'version';
$config->testcase->dtable->fieldList['version']['title'] = $lang->testcase->version;
$config->testcase->dtable->fieldList['version']['type']  = 'text';
$config->testcase->dtable->fieldList['version']['group'] = '5';

$config->testcase->dtable->fieldList['lastEditedBy']['name']  = 'lastEditedBy';
$config->testcase->dtable->fieldList['lastEditedBy']['title'] = $lang->testcase->lastEditedBy;
$config->testcase->dtable->fieldList['lastEditedBy']['type']  = 'user';
$config->testcase->dtable->fieldList['lastEditedBy']['group'] = '6';

$config->testcase->dtable->fieldList['lastEditedDate']['name']  = 'lastEditedDate';
$config->testcase->dtable->fieldList['lastEditedDate']['title'] = $lang->testcase->lastEditedDate;
$config->testcase->dtable->fieldList['lastEditedDate']['type']  = 'date';
$config->testcase->dtable->fieldList['lastEditedDate']['group'] = '6';

$config->testcase->dtable->fieldList['actions']['name']     = 'actions';
$config->testcase->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testcase->dtable->fieldList['actions']['type']     = 'actions';
$config->testcase->dtable->fieldList['actions']['sortType'] = false;
$config->testcase->dtable->fieldList['actions']['list']     = $config->testcase->actionList;
$config->testcase->dtable->fieldList['actions']['menu']     = array();
$config->testcase->dtable->fieldList['actions']['group']    = '7';
