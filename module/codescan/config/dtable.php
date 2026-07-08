<?php
global $app, $lang;
$config->codescan->dtable = new stdclass();

$config->codescan->dtable->fieldList['id']['title'] = 'ID';
$config->codescan->dtable->fieldList['id']['type']  = 'id';

$config->codescan->dtable->fieldList['name']['title']       = $lang->codescan->name;
$config->codescan->dtable->fieldList['name']['name']        = 'name';
$config->codescan->dtable->fieldList['name']['fixed']       = 'left';
$config->codescan->dtable->fieldList['name']['type']        = 'shortTitle';
$config->codescan->dtable->fieldList['name']['sortType']    = true;
$config->codescan->dtable->fieldList['name']['width']       = '200';
$config->codescan->dtable->fieldList['name']['hint']        = true;
$config->codescan->dtable->fieldList['name']['show']        = true;
$config->codescan->dtable->fieldList['name']['required']    = true;
$config->codescan->dtable->fieldList['name']['checkbox']    = false;
$config->codescan->dtable->fieldList['name']['link']        = array('module' => 'codescan', 'method' => 'view', 'params' => 'ruleID={id}');

$config->codescan->dtable->fieldList['lang']['title']    = $lang->codescan->language;
$config->codescan->dtable->fieldList['lang']['name']     = 'lang';
$config->codescan->dtable->fieldList['lang']['sortType'] = true;
$config->codescan->dtable->fieldList['lang']['width']    = '60';
$config->codescan->dtable->fieldList['lang']['hint']     = true;
$config->codescan->dtable->fieldList['lang']['show']     = true;

$config->codescan->dtable->fieldList['plugin']['title']    = $lang->codescan->tool;
$config->codescan->dtable->fieldList['plugin']['name']     = 'plugin';
$config->codescan->dtable->fieldList['plugin']['sortType'] = true;
$config->codescan->dtable->fieldList['plugin']['width']    = '60';
$config->codescan->dtable->fieldList['plugin']['hint']     = true;
$config->codescan->dtable->fieldList['plugin']['show']     = true;

$config->codescan->dtable->fieldList['type']['title']    = $lang->codescan->type;
$config->codescan->dtable->fieldList['type']['name']     = 'type';
$config->codescan->dtable->fieldList['type']['sortType'] = true;
$config->codescan->dtable->fieldList['type']['width']    = '20';
$config->codescan->dtable->fieldList['type']['hint']     = true;
$config->codescan->dtable->fieldList['type']['show']     = true;
$config->codescan->dtable->fieldList['type']['map']      = $lang->codescan->typeList;

$config->codescan->dtable->fieldList['priority']['title']    = $lang->codescan->severity;
$config->codescan->dtable->fieldList['priority']['name']     = 'priority';
$config->codescan->dtable->fieldList['priority']['sortType'] = true;
$config->codescan->dtable->fieldList['priority']['width']    = '50';
$config->codescan->dtable->fieldList['priority']['hint']     = true;
$config->codescan->dtable->fieldList['priority']['show']     = true;
$config->codescan->dtable->fieldList['priority']['map']      = $lang->codescan->severityList;

$config->codescan->dtable->fieldList['tag']['title'] = $lang->codescan->tag;
$config->codescan->dtable->fieldList['tag']['name']  = 'tag';
$config->codescan->dtable->fieldList['tag']['width'] = '120';
$config->codescan->dtable->fieldList['tag']['hint']  = true;
$config->codescan->dtable->fieldList['tag']['show']  = true;

$config->codescan->dtable->fieldList['status']['title']    = $lang->codescan->status;
$config->codescan->dtable->fieldList['status']['map']      = $lang->codescan->statusList;
$config->codescan->dtable->fieldList['status']['sortType'] = true;
$config->codescan->dtable->fieldList['status']['show']     = true;
$config->codescan->dtable->fieldList['status']['width']    = '40';

$config->codescan->dtable->fieldList['description']['title']    = $lang->codescan->synopsis;
$config->codescan->dtable->fieldList['description']['type']     = 'html';
$config->codescan->dtable->fieldList['description']['name']     = 'desc';
$config->codescan->dtable->fieldList['description']['sortType'] = true;
$config->codescan->dtable->fieldList['description']['width']    = '180';
$config->codescan->dtable->fieldList['description']['hint']     = true;
$config->codescan->dtable->fieldList['description']['show']     = true;

$config->codescan->dtable->fieldList['actions']['name']  = 'actions';
$config->codescan->dtable->fieldList['actions']['title'] = $lang->actions;
$config->codescan->dtable->fieldList['actions']['type']  = 'actions';
$config->codescan->dtable->fieldList['actions']['width'] = '80';
$config->codescan->dtable->fieldList['actions']['menu']  = array('enable|disable');
$config->codescan->dtable->fieldList['actions']['list']  = $config->codescan->actionList;

$config->codescan->ruleset->dtable = new stdclass();
$config->codescan->ruleset->dtable->fieldList['id']['title'] = 'ID';
$config->codescan->ruleset->dtable->fieldList['id']['type']  = 'id';

$config->codescan->ruleset->dtable->fieldList['name']['title']    = $lang->codescan->name;
$config->codescan->ruleset->dtable->fieldList['name']['name']     = 'name';
$config->codescan->ruleset->dtable->fieldList['name']['fixed']    = 'left';
$config->codescan->ruleset->dtable->fieldList['name']['type']     = 'shortTitle';
$config->codescan->ruleset->dtable->fieldList['name']['sortType'] = true;
$config->codescan->ruleset->dtable->fieldList['name']['width']    = '300';
$config->codescan->ruleset->dtable->fieldList['name']['hint']     = true;
$config->codescan->ruleset->dtable->fieldList['name']['show']     = true;
$config->codescan->ruleset->dtable->fieldList['name']['required'] = true;
$config->codescan->ruleset->dtable->fieldList['name']['checkbox'] = false;
$config->codescan->ruleset->dtable->fieldList['name']['link']     = array('module' => 'codescan', 'method' => 'rulesetView', 'params' => 'rulesetID={id}');

$config->codescan->ruleset->dtable->fieldList['lang']['title']    = $lang->codescan->language;
$config->codescan->ruleset->dtable->fieldList['lang']['name']     = 'lang';
$config->codescan->ruleset->dtable->fieldList['lang']['sortType'] = true;
$config->codescan->ruleset->dtable->fieldList['lang']['width']    = '60';
$config->codescan->ruleset->dtable->fieldList['lang']['hint']     = true;
$config->codescan->ruleset->dtable->fieldList['lang']['show']     = true;

$config->codescan->ruleset->dtable->fieldList['plugin']['title']    = $lang->codescan->tool;
$config->codescan->ruleset->dtable->fieldList['plugin']['name']     = 'plugin';
$config->codescan->ruleset->dtable->fieldList['plugin']['sortType'] = true;
$config->codescan->ruleset->dtable->fieldList['plugin']['width']    = '60';
$config->codescan->ruleset->dtable->fieldList['plugin']['hint']     = true;
$config->codescan->ruleset->dtable->fieldList['plugin']['show']     = true;

$config->codescan->ruleset->dtable->fieldList['rulesCount']['title']       = $lang->codescan->ruleNum;
$config->codescan->ruleset->dtable->fieldList['rulesCount']['sortType']    = true;
$config->codescan->ruleset->dtable->fieldList['rulesCount']['width']       = '20';
$config->codescan->ruleset->dtable->fieldList['rulesCount']['hint']        = true;
$config->codescan->ruleset->dtable->fieldList['rulesCount']['show']        = true;
$config->codescan->ruleset->dtable->fieldList['rulesCount']['link']        = array('module' => 'codescan', 'method' => 'rulesetView', 'params' => 'rulesetID={id}');
$config->codescan->ruleset->dtable->fieldList['rulesCount']['data-toggle'] = 'modal';
$config->codescan->ruleset->dtable->fieldList['rulesCount']['data-size']   = 'lg';

$config->codescan->ruleset->dtable->fieldList['status']['title']    = $lang->codescan->status;
$config->codescan->ruleset->dtable->fieldList['status']['map']      = $lang->codescan->statusList;
$config->codescan->ruleset->dtable->fieldList['status']['sortType'] = true;
$config->codescan->ruleset->dtable->fieldList['status']['show']     = true;
$config->codescan->ruleset->dtable->fieldList['status']['width']    = '40';

$config->codescan->ruleset->dtable->fieldList['desc']['title']    = $lang->codescan->desc;
$config->codescan->ruleset->dtable->fieldList['desc']['type']     = 'html';
$config->codescan->ruleset->dtable->fieldList['desc']['sortType'] = false;
$config->codescan->ruleset->dtable->fieldList['desc']['width']    = '180';
$config->codescan->ruleset->dtable->fieldList['desc']['hint']     = true;
$config->codescan->ruleset->dtable->fieldList['desc']['show']     = true;

$config->codescan->ruleset->dtable->fieldList['createdBy']['title']    = $lang->codescan->createdBy;
$config->codescan->ruleset->dtable->fieldList['createdBy']['type']     = 'user';
$config->codescan->ruleset->dtable->fieldList['createdBy']['sortType'] = true;
$config->codescan->ruleset->dtable->fieldList['createdBy']['width']    = '120';
$config->codescan->ruleset->dtable->fieldList['createdBy']['hint']     = true;
$config->codescan->ruleset->dtable->fieldList['createdBy']['show']     = false;

$config->codescan->ruleset->dtable->fieldList['createdDate']['title']    = $lang->codescan->createTime;
$config->codescan->ruleset->dtable->fieldList['createdDate']['type']     = 'dateTime';
$config->codescan->ruleset->dtable->fieldList['createdDate']['sortType'] = true;
$config->codescan->ruleset->dtable->fieldList['createdDate']['width']    = '120';
$config->codescan->ruleset->dtable->fieldList['createdDate']['hint']     = true;
$config->codescan->ruleset->dtable->fieldList['createdDate']['show']     = false;

$config->codescan->ruleset->dtable->fieldList['updatedBy']['title']    = $lang->codescan->updatedBy;
$config->codescan->ruleset->dtable->fieldList['updatedBy']['type']     = 'user';
$config->codescan->ruleset->dtable->fieldList['updatedBy']['sortType'] = true;
$config->codescan->ruleset->dtable->fieldList['updatedBy']['width']    = '120';
$config->codescan->ruleset->dtable->fieldList['updatedBy']['hint']     = true;
$config->codescan->ruleset->dtable->fieldList['updatedBy']['show']     = false;

$config->codescan->ruleset->dtable->fieldList['updatedDate']['title']    = $lang->codescan->updateTime;
$config->codescan->ruleset->dtable->fieldList['updatedDate']['type']     = 'dateTime';
$config->codescan->ruleset->dtable->fieldList['updatedDate']['sortType'] = true;
$config->codescan->ruleset->dtable->fieldList['updatedDate']['width']    = '120';
$config->codescan->ruleset->dtable->fieldList['updatedDate']['hint']     = true;
$config->codescan->ruleset->dtable->fieldList['updatedDate']['show']     = false;

$config->codescan->ruleset->dtable->fieldList['actions']['name']  = 'actions';
$config->codescan->ruleset->dtable->fieldList['actions']['title'] = $lang->actions;
$config->codescan->ruleset->dtable->fieldList['actions']['type']  = 'actions';
$config->codescan->ruleset->dtable->fieldList['actions']['width'] = '100';
$config->codescan->ruleset->dtable->fieldList['actions']['menu']  = array('link', 'enable|disable', 'editset', 'deleteset');
$config->codescan->ruleset->dtable->fieldList['actions']['list']  = $config->codescan->ruleset->actionList;

$config->codescan->solution->dtable = new stdclass();
$config->codescan->solution->dtable->fieldList['id']['title'] = $lang->idAB;
$config->codescan->solution->dtable->fieldList['id']['type']  = 'id';

$config->codescan->solution->dtable->fieldList['name']['title']    = $lang->codescan->name;
$config->codescan->solution->dtable->fieldList['name']['type']     = 'shortTitle';
$config->codescan->solution->dtable->fieldList['name']['show']     = true;
$config->codescan->solution->dtable->fieldList['name']['required'] = true;
$config->codescan->solution->dtable->fieldList['name']['link']     = array('module' => 'codescan', 'method' => 'solutionView', 'params' => 'solutionID={id}');

$config->codescan->solution->dtable->fieldList['lang']['title']    = $lang->codescan->language;
$config->codescan->solution->dtable->fieldList['lang']['width']    = '100';
$config->codescan->solution->dtable->fieldList['lang']['show']     = true;
$config->codescan->solution->dtable->fieldList['lang']['required'] = false;
$config->codescan->solution->dtable->fieldList['lang']['map']      = array();

$config->codescan->solution->dtable->fieldList['plugin']['title']    = $lang->codescan->tool;
$config->codescan->solution->dtable->fieldList['plugin']['name']     = 'plugin';
$config->codescan->solution->dtable->fieldList['plugin']['width']    = '100';
$config->codescan->solution->dtable->fieldList['plugin']['hint']     = true;
$config->codescan->solution->dtable->fieldList['plugin']['show']     = true;
$config->codescan->solution->dtable->fieldList['plugin']['map']      = array();

$config->codescan->solution->dtable->fieldList['ruleSetsCount']['title']       = $lang->codescan->setNum;
$config->codescan->solution->dtable->fieldList['ruleSetsCount']['width']       = '110';
$config->codescan->solution->dtable->fieldList['ruleSetsCount']['show']        = true;
$config->codescan->solution->dtable->fieldList['ruleSetsCount']['required']    = false;
$config->codescan->solution->dtable->fieldList['ruleSetsCount']['sortType']    = true;
$config->codescan->solution->dtable->fieldList['ruleSetsCount']['link']        = array('module' => 'codescan', 'method' => 'solutionView', 'params' => 'solutionID={id}&onlyRuleSet=1');
$config->codescan->solution->dtable->fieldList['ruleSetsCount']['data-toggle'] = 'modal';
$config->codescan->solution->dtable->fieldList['ruleSetsCount']['data-size']   = 'lg';

$config->codescan->solution->dtable->fieldList['rulesCount']['title']    = $lang->codescan->ruleNum;
$config->codescan->solution->dtable->fieldList['rulesCount']['width']    = '100';
$config->codescan->solution->dtable->fieldList['rulesCount']['show']     = true;
$config->codescan->solution->dtable->fieldList['rulesCount']['required'] = false;

$config->codescan->solution->dtable->fieldList['status']['title']     = $lang->codescan->status;
$config->codescan->solution->dtable->fieldList['status']['type']      = 'status';
$config->codescan->solution->dtable->fieldList['status']['width']     = '100';
$config->codescan->solution->dtable->fieldList['status']['show']      = true;
$config->codescan->solution->dtable->fieldList['status']['required']  = false;
$config->codescan->solution->dtable->fieldList['status']['statusMap'] = $lang->codescan->statusList;

$config->codescan->solution->dtable->fieldList['desc']['title']    = $lang->codescan->desc;
$config->codescan->solution->dtable->fieldList['desc']['type']     = 'desc';
$config->codescan->solution->dtable->fieldList['desc']['show']     = true;
$config->codescan->solution->dtable->fieldList['desc']['required'] = false;

$config->codescan->solution->dtable->fieldList['createdBy']['title']    = $lang->codescan->createdBy;
$config->codescan->solution->dtable->fieldList['createdBy']['type']     = 'user';
$config->codescan->solution->dtable->fieldList['createdBy']['show']     = false;
$config->codescan->solution->dtable->fieldList['createdBy']['required'] = false;

$config->codescan->solution->dtable->fieldList['createdDate']['title']    = $lang->codescan->createTime;
$config->codescan->solution->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->codescan->solution->dtable->fieldList['createdDate']['show']     = false;
$config->codescan->solution->dtable->fieldList['createdDate']['required'] = false;

$config->codescan->solution->dtable->fieldList['editedBy']['title']    = $lang->codescan->updatedBy;
$config->codescan->solution->dtable->fieldList['editedBy']['type']     = 'user';
$config->codescan->solution->dtable->fieldList['editedBy']['show']     = false;
$config->codescan->solution->dtable->fieldList['editedBy']['required'] = false;

$config->codescan->solution->dtable->fieldList['editedDate']['title']    = $lang->codescan->updateTime;
$config->codescan->solution->dtable->fieldList['editedDate']['type']     = 'datetime';
$config->codescan->solution->dtable->fieldList['editedDate']['show']     = false;
$config->codescan->solution->dtable->fieldList['editedDate']['required'] = false;

$config->codescan->solution->dtable->fieldList['actions']['name']  = 'actions';
$config->codescan->solution->dtable->fieldList['actions']['title'] = $lang->actions;
$config->codescan->solution->dtable->fieldList['actions']['type']  = 'actions';
$config->codescan->solution->dtable->fieldList['actions']['width'] = '100';
$config->codescan->solution->dtable->fieldList['actions']['menu']  = array('linkset', 'enable|disable', 'editsolution', 'deletesolution');
$config->codescan->solution->dtable->fieldList['actions']['list']  = $config->codescan->solution->actionList;

$config->codescan->plan->dtable = new stdclass();
$config->codescan->plan->dtable->fieldList['id']['title'] = $lang->idAB;
$config->codescan->plan->dtable->fieldList['id']['type']  = 'id';

$config->codescan->plan->dtable->fieldList['name']['title']    = $lang->codescan->name;
$config->codescan->plan->dtable->fieldList['name']['width']    = '200';
$config->codescan->plan->dtable->fieldList['name']['type']     = 'shortTitle';
$config->codescan->plan->dtable->fieldList['name']['show']     = true;
$config->codescan->plan->dtable->fieldList['name']['required'] = true;
$config->codescan->plan->dtable->fieldList['name']['link']     = array('module' => 'codescan', 'method' => 'planView', 'params' => 'serviceRepoID={repo_id}&planID={id}&repoID={repo}&type=view');
$config->codescan->plan->dtable->fieldList['name']['order']    = 10;

$config->codescan->plan->dtable->fieldList['latestExecStatus']['title']     = $lang->codescan->latestExecStatus;
$config->codescan->plan->dtable->fieldList['latestExecStatus']['type']      = 'status';
$config->codescan->plan->dtable->fieldList['latestExecStatus']['width']     = '120';
$config->codescan->plan->dtable->fieldList['latestExecStatus']['show']      = true;
$config->codescan->plan->dtable->fieldList['latestExecStatus']['required']  = false;
$config->codescan->plan->dtable->fieldList['latestExecStatus']['sortType']  = true;
$config->codescan->plan->dtable->fieldList['latestExecStatus']['statusMap'] = $lang->codescan->latestExecStatusList;
$config->codescan->plan->dtable->fieldList['latestExecStatus']['order']     = 20;

$config->codescan->plan->dtable->fieldList['latestExecResult']['title']     = $lang->codescan->latestScanResult;
$config->codescan->plan->dtable->fieldList['latestExecResult']['type']      = 'status';
$config->codescan->plan->dtable->fieldList['latestExecResult']['width']     = '120';
$config->codescan->plan->dtable->fieldList['latestExecResult']['show']      = true;
$config->codescan->plan->dtable->fieldList['latestExecResult']['required']  = false;
$config->codescan->plan->dtable->fieldList['latestExecResult']['sortType']  = true;
$config->codescan->plan->dtable->fieldList['latestExecResult']['statusMap'] = $lang->codescan->latestScanResultList;
$config->codescan->plan->dtable->fieldList['latestExecResult']['order']     = 30;

$config->codescan->plan->dtable->fieldList['repoID']['title']    = $lang->codescan->repo;
$config->codescan->plan->dtable->fieldList['repoID']['name']     = 'repoID';
$config->codescan->plan->dtable->fieldList['repoID']['width']    = '100';
$config->codescan->plan->dtable->fieldList['repoID']['show']     = true;
$config->codescan->plan->dtable->fieldList['repoID']['hint']     = true;
$config->codescan->plan->dtable->fieldList['repoID']['required'] = false;
$config->codescan->plan->dtable->fieldList['repoID']['sortType'] = false;
$config->codescan->plan->dtable->fieldList['repoID']['order']    = 40;

$config->codescan->plan->dtable->fieldList['scanBranch']['title']    = $lang->codescan->branch;
$config->codescan->plan->dtable->fieldList['scanBranch']['width']    = '200';
$config->codescan->plan->dtable->fieldList['scanBranch']['hint']     = true;
$config->codescan->plan->dtable->fieldList['scanBranch']['show']     = true;
$config->codescan->plan->dtable->fieldList['scanBranch']['sortType'] = false;
$config->codescan->plan->dtable->fieldList['scanBranch']['order']    = 50;

$config->codescan->plan->dtable->fieldList['scanType']['title']    = $lang->codescan->scope;
$config->codescan->plan->dtable->fieldList['scanType']['hint']     = true;
$config->codescan->plan->dtable->fieldList['scanType']['width']    = '100';
$config->codescan->plan->dtable->fieldList['scanType']['show']     = true;
$config->codescan->plan->dtable->fieldList['scanType']['sortType'] = true;
$config->codescan->plan->dtable->fieldList['scanType']['required'] = false;
$config->codescan->plan->dtable->fieldList['scanType']['map']      = $lang->codescan->scopeList;
$config->codescan->plan->dtable->fieldList['scanType']['order']    = 70;

$config->codescan->plan->dtable->fieldList['solutions']['title']    = $lang->codescan->solution;
$config->codescan->plan->dtable->fieldList['solutions']['width']    = '100';
$config->codescan->plan->dtable->fieldList['solutions']['show']     = true;
$config->codescan->plan->dtable->fieldList['solutions']['hint']     = true;
$config->codescan->plan->dtable->fieldList['solutions']['required'] = false;
$config->codescan->plan->dtable->fieldList['solutions']['sortType'] = false;
$config->codescan->plan->dtable->fieldList['solutions']['map']      = array();
$config->codescan->plan->dtable->fieldList['solutions']['order']    = 80;

$config->codescan->plan->dtable->fieldList['latestScanTime']['title']    = $lang->codescan->latestScanTime;
$config->codescan->plan->dtable->fieldList['latestScanTime']['type']     = 'datetime';
$config->codescan->plan->dtable->fieldList['latestScanTime']['hint']     = true;
$config->codescan->plan->dtable->fieldList['latestScanTime']['show']     = true;
$config->codescan->plan->dtable->fieldList['latestScanTime']['sortType'] = true;
$config->codescan->plan->dtable->fieldList['latestScanTime']['required'] = false;
$config->codescan->plan->dtable->fieldList['latestScanTime']['order']    = 90;

$config->codescan->plan->dtable->fieldList['actions']['name']  = 'actions';
$config->codescan->plan->dtable->fieldList['actions']['title'] = $lang->actions;
$config->codescan->plan->dtable->fieldList['actions']['type']  = 'actions';
$config->codescan->plan->dtable->fieldList['actions']['width'] = '100';
$config->codescan->plan->dtable->fieldList['actions']['menu']  = array('exec', 'task', 'editPlan', 'deletePlan');
$config->codescan->plan->dtable->fieldList['actions']['list']  = $config->codescan->plan->actionList;

$config->codescan->trigger->dtable = new stdclass();
$config->codescan->trigger->dtable->fieldList['id']['title']    = $lang->idAB;
$config->codescan->trigger->dtable->fieldList['id']['type']     = 'id';
$config->codescan->trigger->dtable->fieldList['id']['sortType'] = false;

$config->codescan->trigger->dtable->fieldList['name']['title']    = $lang->codescan->name;
$config->codescan->trigger->dtable->fieldList['name']['type']     = 'shortTitle';
$config->codescan->trigger->dtable->fieldList['name']['show']     = true;
$config->codescan->trigger->dtable->fieldList['name']['required'] = true;
$config->codescan->trigger->dtable->fieldList['name']['sortType'] = false;

$config->codescan->trigger->dtable->fieldList['type']['title']    = $lang->codescan->triggerType;
$config->codescan->trigger->dtable->fieldList['type']['width']    = '100';
$config->codescan->trigger->dtable->fieldList['type']['show']     = true;
$config->codescan->trigger->dtable->fieldList['type']['hint']     = true;
$config->codescan->trigger->dtable->fieldList['type']['required'] = false;
$config->codescan->trigger->dtable->fieldList['type']['sortType'] = false;
$config->codescan->trigger->dtable->fieldList['type']['map']      = $lang->codescan->triggerTypeList;

$config->codescan->trigger->dtable->fieldList['scanType']['title']    = $lang->codescan->scope;
$config->codescan->trigger->dtable->fieldList['scanType']['hint']     = true;
$config->codescan->trigger->dtable->fieldList['scanType']['width']    = '100';
$config->codescan->trigger->dtable->fieldList['scanType']['show']     = true;
$config->codescan->trigger->dtable->fieldList['scanType']['required'] = false;
$config->codescan->trigger->dtable->fieldList['scanType']['map']      = $lang->codescan->scopeList;

$config->codescan->trigger->dtable->fieldList['solutionName']['title']    = $lang->codescan->solution;
$config->codescan->trigger->dtable->fieldList['solutionName']['width']    = '100';
$config->codescan->trigger->dtable->fieldList['solutionName']['show']     = true;
$config->codescan->trigger->dtable->fieldList['solutionName']['hint']     = true;
$config->codescan->trigger->dtable->fieldList['solutionName']['required'] = false;
$config->codescan->trigger->dtable->fieldList['solutionName']['sortType'] = false;
$config->codescan->trigger->dtable->fieldList['solutionName']['map']      = array();

$config->codescan->trigger->dtable->fieldList['latestScanTime']['title']    = $lang->codescan->latestScanTime;
$config->codescan->trigger->dtable->fieldList['latestScanTime']['type']     = 'datetime';
$config->codescan->trigger->dtable->fieldList['latestScanTime']['hint']     = true;
$config->codescan->trigger->dtable->fieldList['latestScanTime']['show']     = true;
$config->codescan->trigger->dtable->fieldList['latestScanTime']['required'] = false;
$config->codescan->trigger->dtable->fieldList['latestScanTime']['sortType'] = false;

$config->codescan->trigger->dtable->fieldList['latestExecStatus']['title']     = $lang->codescan->latestExecStatus;
$config->codescan->trigger->dtable->fieldList['latestExecStatus']['type']      = 'status';
$config->codescan->trigger->dtable->fieldList['latestExecStatus']['width']     = '100';
$config->codescan->trigger->dtable->fieldList['latestExecStatus']['show']      = true;
$config->codescan->trigger->dtable->fieldList['latestExecStatus']['required']  = false;
$config->codescan->trigger->dtable->fieldList['latestExecStatus']['statusMap'] = $lang->codescan->latestExecStatusList;
$config->codescan->trigger->dtable->fieldList['latestExecStatus']['sortType']  = false;

$config->codescan->trigger->dtable->fieldList['result']['title']     = $lang->codescan->latestScanResult;
$config->codescan->trigger->dtable->fieldList['result']['type']      = 'status';
$config->codescan->trigger->dtable->fieldList['result']['show']      = true;
$config->codescan->trigger->dtable->fieldList['result']['required']  = false;
$config->codescan->trigger->dtable->fieldList['result']['statusMap'] = $lang->codescan->latestScanResultList;
$config->codescan->trigger->dtable->fieldList['result']['sortType']  = false;

$config->codescan->trigger->dtable->fieldList['latestScan']['title']    = $lang->codescan->latestScan;
$config->codescan->trigger->dtable->fieldList['latestScan']['type']     = 'html';
$config->codescan->trigger->dtable->fieldList['latestScan']['show']     = true;
$config->codescan->trigger->dtable->fieldList['latestScan']['required'] = false;
$config->codescan->trigger->dtable->fieldList['latestScan']['sortType'] = false;

$config->codescan->trigger->dtable->fieldList['actions']['name']  = 'actions';
$config->codescan->trigger->dtable->fieldList['actions']['title'] = $lang->actions;
$config->codescan->trigger->dtable->fieldList['actions']['type']  = 'actions';
$config->codescan->trigger->dtable->fieldList['actions']['width'] = '100';
$config->codescan->trigger->dtable->fieldList['actions']['menu']  = array('editTrigger', 'deleteTrigger');
$config->codescan->trigger->dtable->fieldList['actions']['list']  = $config->codescan->trigger->actionList;

$config->codescan->task->dtable = new stdclass();
$config->codescan->task->dtable->fieldList['id']['title'] = $lang->idAB;
$config->codescan->task->dtable->fieldList['id']['type']  = 'id';
$config->codescan->task->dtable->fieldList['id']['order']  = 10;

$config->codescan->task->dtable->fieldList['name']['title']    = $lang->codescan->name;
$config->codescan->task->dtable->fieldList['name']['type']     = 'shortTitle';
$config->codescan->task->dtable->fieldList['name']['show']     = true;
$config->codescan->task->dtable->fieldList['name']['sortType'] = false;
$config->codescan->task->dtable->fieldList['name']['required'] = true;
$config->codescan->task->dtable->fieldList['name']['link']     = array('module' => 'codescan', 'method' => 'taskView', 'params' => 'taskID={id}');
$config->codescan->task->dtable->fieldList['name']['order']    = 20;

$config->codescan->task->dtable->fieldList['repo']['title']    = $lang->codescan->repo;
$config->codescan->task->dtable->fieldList['repo']['width']    = '100';
$config->codescan->task->dtable->fieldList['repo']['show']     = true;
$config->codescan->task->dtable->fieldList['repo']['hint']     = true;
$config->codescan->task->dtable->fieldList['repo']['required'] = false;
$config->codescan->task->dtable->fieldList['repo']['sortType'] = true;
$config->codescan->task->dtable->fieldList['repo']['order']    = 30;

$config->codescan->task->dtable->fieldList['branch']['title']    = $lang->codescan->branch;
$config->codescan->task->dtable->fieldList['branch']['width']    = '100';
$config->codescan->task->dtable->fieldList['branch']['hint']     = true;
$config->codescan->task->dtable->fieldList['branch']['show']     = true;
$config->codescan->task->dtable->fieldList['branch']['sortType'] = false;
$config->codescan->task->dtable->fieldList['branch']['order']    = 40;

$config->codescan->task->dtable->fieldList['planID']['title']    = $lang->codescan->scanPlan;
$config->codescan->task->dtable->fieldList['planID']['name']     = 'planName';
$config->codescan->task->dtable->fieldList['planID']['width']    = '100';
$config->codescan->task->dtable->fieldList['planID']['hint']     = true;
$config->codescan->task->dtable->fieldList['planID']['show']     = true;
$config->codescan->task->dtable->fieldList['planID']['sortType'] = false;
$config->codescan->task->dtable->fieldList['planID']['order']    = 50;

$config->codescan->task->dtable->fieldList['status']['title']     = $lang->codescan->runStatus;
$config->codescan->task->dtable->fieldList['status']['type']      = 'status';
$config->codescan->task->dtable->fieldList['status']['width']     = '100';
$config->codescan->task->dtable->fieldList['status']['show']      = true;
$config->codescan->task->dtable->fieldList['status']['required']  = false;
$config->codescan->task->dtable->fieldList['status']['statusMap'] = $lang->codescan->latestExecStatusList;
$config->codescan->task->dtable->fieldList['status']['order']     = 60;

$config->codescan->task->dtable->fieldList['result']['title']     = $lang->codescan->result;
$config->codescan->task->dtable->fieldList['result']['type']      = 'status';
$config->codescan->task->dtable->fieldList['result']['show']      = true;
$config->codescan->task->dtable->fieldList['result']['required']  = false;
$config->codescan->task->dtable->fieldList['result']['statusMap'] = $lang->codescan->latestScanResultList;
$config->codescan->task->dtable->fieldList['result']['order']     = 70;

$config->codescan->task->dtable->fieldList['issueCount']['title']       = $lang->codescan->issueCount;
$config->codescan->task->dtable->fieldList['issueCount']['width']       = '70';
$config->codescan->task->dtable->fieldList['issueCount']['show']        = true;
$config->codescan->task->dtable->fieldList['issueCount']['required']    = false;
$config->codescan->task->dtable->fieldList['issueCount']['sortType']    = false;
$config->codescan->task->dtable->fieldList['issueCount']['link']        = array('module' => 'codescan', 'method' => 'issue', 'params' => 'repoID={repo}&taskID={id}&serviceRepoID={repo_id}&type=all');
$config->codescan->task->dtable->fieldList['issueCount']['data-toggle'] = 'modal';
$config->codescan->task->dtable->fieldList['issueCount']['data-size']   = 'lg';
$config->codescan->task->dtable->fieldList['issueCount']['order']       = 80;

$config->codescan->task->dtable->fieldList['triggerType']['title']     = $lang->codescan->triggerType;
$config->codescan->task->dtable->fieldList['triggerType']['hint']      = true;
$config->codescan->task->dtable->fieldList['triggerType']['width']     = '100';
$config->codescan->task->dtable->fieldList['triggerType']['show']      = true;
$config->codescan->task->dtable->fieldList['triggerType']['required']  = false;
$config->codescan->task->dtable->fieldList['triggerType']['map']       = $lang->codescan->triggerTypeList;
$config->codescan->task->dtable->fieldList['triggerType']['order']     = 90;

$config->codescan->task->dtable->fieldList['scanType']['title']    = $lang->codescan->scope;
$config->codescan->task->dtable->fieldList['scanType']['hint']     = true;
$config->codescan->task->dtable->fieldList['scanType']['width']    = '100';
$config->codescan->task->dtable->fieldList['scanType']['show']     = true;
$config->codescan->task->dtable->fieldList['scanType']['required'] = false;
$config->codescan->task->dtable->fieldList['scanType']['map']      = $lang->codescan->scopeList;
$config->codescan->task->dtable->fieldList['scanType']['order']    = 100;

$config->codescan->task->dtable->fieldList['startTime']['title']    = $lang->codescan->startTime;
$config->codescan->task->dtable->fieldList['startTime']['type']     = 'datetime';
$config->codescan->task->dtable->fieldList['startTime']['hint']     = true;
$config->codescan->task->dtable->fieldList['startTime']['show']     = true;
$config->codescan->task->dtable->fieldList['startTime']['required'] = false;
$config->codescan->task->dtable->fieldList['startTime']['order']    = 110;

$config->codescan->task->dtable->fieldList['runTime']['title']    = $lang->codescan->runTime;
$config->codescan->task->dtable->fieldList['runTime']['width']    = '50';
$config->codescan->task->dtable->fieldList['runTime']['hint']     = true;
$config->codescan->task->dtable->fieldList['runTime']['show']     = true;
$config->codescan->task->dtable->fieldList['runTime']['required'] = false;
$config->codescan->task->dtable->fieldList['runTime']['order']    = 120;

$config->codescan->task->dtable->fieldList['endTime']['title']    = $lang->codescan->endTime;
$config->codescan->task->dtable->fieldList['endTime']['type']     = 'datetime';
$config->codescan->task->dtable->fieldList['endTime']['hint']     = true;
$config->codescan->task->dtable->fieldList['endTime']['show']     = false;
$config->codescan->task->dtable->fieldList['endTime']['required'] = false;
$config->codescan->task->dtable->fieldList['endTime']['order']    = 130;

$config->codescan->task->dtable->fieldList['actions']['name']  = 'actions';
$config->codescan->task->dtable->fieldList['actions']['title'] = $lang->actions;
$config->codescan->task->dtable->fieldList['actions']['type']  = 'actions';
$config->codescan->task->dtable->fieldList['actions']['width'] = '100';
// $config->codescan->task->dtable->fieldList['actions']['menu']  = array('resend', 'issue', 'taskLog');
$config->codescan->task->dtable->fieldList['actions']['menu']  = array('resend', 'issue');
$config->codescan->task->dtable->fieldList['actions']['list']  = $config->codescan->task->actionList;

$config->codescan->issue->dtable = new stdclass();
$config->codescan->issue->dtable->fieldList['id']['title'] = $lang->idAB;
$config->codescan->issue->dtable->fieldList['id']['type']  = 'checkID';

$config->codescan->issue->dtable->fieldList['content']['title']    = $lang->codescan->title;
$config->codescan->issue->dtable->fieldList['content']['type']     = 'shortTitle';
$config->codescan->issue->dtable->fieldList['content']['show']     = true;
$config->codescan->issue->dtable->fieldList['content']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['content']['required'] = true;
$config->codescan->issue->dtable->fieldList['content']['checkbox'] = false;

$config->codescan->issue->dtable->fieldList['repoBranch']['title']    = $lang->codescan->scanBranch;
$config->codescan->issue->dtable->fieldList['repoBranch']['width']    = 150;
$config->codescan->issue->dtable->fieldList['repoBranch']['show']     = true;
$config->codescan->issue->dtable->fieldList['repoBranch']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['repoBranch']['required'] = false;

$config->codescan->issue->dtable->fieldList['file']['title']    = $lang->codescan->file;
$config->codescan->issue->dtable->fieldList['file']['width']    = 250;
$config->codescan->issue->dtable->fieldList['file']['show']     = true;
$config->codescan->issue->dtable->fieldList['file']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['file']['required'] = false;
$config->codescan->issue->dtable->fieldList['file']['hint']     = true;

$config->codescan->issue->dtable->fieldList['priority']['title']    = $lang->codescan->severity;
$config->codescan->issue->dtable->fieldList['priority']['width']    = 100;
$config->codescan->issue->dtable->fieldList['priority']['show']     = true;
$config->codescan->issue->dtable->fieldList['priority']['sortType'] = true;
$config->codescan->issue->dtable->fieldList['priority']['required'] = false;
$config->codescan->issue->dtable->fieldList['priority']['map']      = $lang->codescan->severityList;

$config->codescan->issue->dtable->fieldList['type']['title']    = $lang->codescan->type;
$config->codescan->issue->dtable->fieldList['type']['width']    = 100;
$config->codescan->issue->dtable->fieldList['type']['show']     = true;
$config->codescan->issue->dtable->fieldList['type']['required'] = false;
$config->codescan->issue->dtable->fieldList['type']['sortType'] = true;
$config->codescan->issue->dtable->fieldList['type']['map']      = $lang->codescan->typeList;

$config->codescan->issue->dtable->fieldList['rulePlugin']['title']    = $lang->codescan->tool;
$config->codescan->issue->dtable->fieldList['rulePlugin']['width']    = 100;
$config->codescan->issue->dtable->fieldList['rulePlugin']['show']     = true;
$config->codescan->issue->dtable->fieldList['rulePlugin']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['rulePlugin']['required'] = false;

$config->codescan->issue->dtable->fieldList['status']['title']     = $lang->codescan->status;
$config->codescan->issue->dtable->fieldList['status']['type']      = 'status';
$config->codescan->issue->dtable->fieldList['status']['show']      = true;
$config->codescan->issue->dtable->fieldList['status']['sortType']  = true;
$config->codescan->issue->dtable->fieldList['status']['required']  = false;
$config->codescan->issue->dtable->fieldList['status']['fixed']     = 'right';
$config->codescan->issue->dtable->fieldList['status']['width']     = 150;
$config->codescan->issue->dtable->fieldList['status']['statusMap'] = $lang->codescan->issueStatusList;

$app->loadLang('bug');
$config->codescan->issue->dtable->fieldList['resolution']['title']    = $lang->bug->resolution;
$config->codescan->issue->dtable->fieldList['resolution']['width']    = 150;
$config->codescan->issue->dtable->fieldList['resolution']['show']     = true;
$config->codescan->issue->dtable->fieldList['resolution']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['resolution']['required'] = false;
$config->codescan->issue->dtable->fieldList['resolution']['map']      = $lang->bug->resolutionList;

$config->codescan->issue->dtable->fieldList['createdAt']['title']    = $lang->codescan->createTime;
$config->codescan->issue->dtable->fieldList['createdAt']['type']     = 'datetime';
$config->codescan->issue->dtable->fieldList['createdAt']['show']     = true;
$config->codescan->issue->dtable->fieldList['createdAt']['sortType'] = true;
$config->codescan->issue->dtable->fieldList['createdAt']['required'] = false;

$config->codescan->issue->dtable->fieldList['planName']['title']    = $lang->codescan->scanPlan;
$config->codescan->issue->dtable->fieldList['planName']['width']    = 150;
$config->codescan->issue->dtable->fieldList['planName']['show']     = false;
$config->codescan->issue->dtable->fieldList['planName']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['planName']['required'] = false;

$config->codescan->issue->dtable->fieldList['triggerName']['title']    = $lang->codescan->triggerName;
$config->codescan->issue->dtable->fieldList['triggerName']['width']    = 150;
$config->codescan->issue->dtable->fieldList['triggerName']['show']     = false;
$config->codescan->issue->dtable->fieldList['triggerName']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['triggerName']['required'] = false;

$config->codescan->issue->dtable->fieldList['ruleID']['title']    = $lang->codescan->ruleID;
$config->codescan->issue->dtable->fieldList['ruleID']['width']    = 150;
$config->codescan->issue->dtable->fieldList['ruleID']['show']     = false;
$config->codescan->issue->dtable->fieldList['ruleID']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['ruleID']['required'] = false;

$config->codescan->issue->dtable->fieldList['ruleName']['title']    = $lang->codescan->ruleName;
$config->codescan->issue->dtable->fieldList['ruleName']['width']    = 150;
$config->codescan->issue->dtable->fieldList['ruleName']['show']     = true;
$config->codescan->issue->dtable->fieldList['ruleName']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['ruleName']['required'] = false;

$config->codescan->issue->dtable->fieldList['resolved']['title']    = $lang->bug->resolvedDate;
$config->codescan->issue->dtable->fieldList['resolved']['type']     = 'datetime';
$config->codescan->issue->dtable->fieldList['resolved']['show']     = false;
$config->codescan->issue->dtable->fieldList['resolved']['sortType'] = false;
$config->codescan->issue->dtable->fieldList['resolved']['required'] = false;
