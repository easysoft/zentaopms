<?php
global $lang, $config;

$config->ai->dtable = new stdclass();

$config->ai->dtable->assistants                  = array();
$config->ai->dtable->assistants['id']            = array('name' => 'id', 'title' => $lang->idAB, 'type' => 'id', 'sortType' => true);
$config->ai->dtable->assistants['name']          = array('name' => 'name', 'title' => $lang->ai->assistant->name, 'flex' => 1, 'sortType' => true, 'type' => 'link', 'link' => array('module' => 'ai', 'method' => 'assistantview', 'params' => 'assistantID={id}'));
$config->ai->dtable->assistants['model']         = array('name' => 'modelId', 'title' => $lang->ai->assistant->refModel, 'flex' => 'auto', 'type' => 'text', 'sortType' => true, 'minWidth' => 120,);
$config->ai->dtable->assistants['status']        = array('name' => 'enabled', 'title' => $lang->statusAB, 'flex' => 'auto', 'type' => 'status', 'statusMap' => $lang->ai->assistant->statusList, 'minWidth' => 80);
$config->ai->dtable->assistants['createdDate']   = array('name' => 'createdDate', 'title' => $lang->ai->assistant->createdDate, 'flex' => 'auto', 'type' => 'datetime', 'minWidth' => 120);
$config->ai->dtable->assistants['publishedDate'] = array('name' => 'publishedDate', 'title' => $lang->ai->assistant->publishedDate, 'flex' => 'auto', 'type' => 'datetime', 'minWidth' => 120,);
$config->ai->dtable->assistants['actions']       = array('title' => $lang->actions, 'type' => 'actions', 'width' => 100, 'fixed' => 'right', 'align' => 'center', 'menu' => $config->ai->actions->assistants, 'list' => $config->ai->actionList);

$config->ai->dtable->prompts = array();
$config->ai->dtable->prompts['id']['title']    = 'ID';
$config->ai->dtable->prompts['id']['type']     = 'id';
$config->ai->dtable->prompts['id']['sortType'] = true;
$config->ai->dtable->prompts['id']['required'] = true;

$config->ai->dtable->prompts['name']['title']    = $lang->ai->prompts->name;
$config->ai->dtable->prompts['name']['sortType'] = true;
$config->ai->dtable->prompts['name']['required'] = true;
$config->ai->dtable->prompts['name']['link']     = array('module' => 'ai', 'method' => 'promptview', 'params' => "id={id}");

$config->ai->dtable->prompts['status']['title']    = $lang->ai->prompts->stage;
$config->ai->dtable->prompts['status']['sortType'] = true;
$config->ai->dtable->prompts['status']['required'] = true;
$config->ai->dtable->prompts['status']['map']      = $lang->ai->prompts->statuses;

$config->ai->dtable->prompts['createdBy']['title']    = $lang->ai->prompts->createdBy;
$config->ai->dtable->prompts['createdBy']['sortType'] = true;
$config->ai->dtable->prompts['createdBy']['required'] = true;
$config->ai->dtable->prompts['createdBy']['type']     = 'user';

$config->ai->dtable->prompts['createdDate']['title']    = $lang->ai->prompts->createdDate;
$config->ai->dtable->prompts['createdDate']['sortType'] = true;
$config->ai->dtable->prompts['createdDate']['required'] = true;

$config->ai->dtable->prompts['targetFormLabel']['title']    = $lang->ai->prompts->targetForm;
$config->ai->dtable->prompts['targetFormLabel']['sortType'] = false;
$config->ai->dtable->prompts['targetFormLabel']['required'] = true;

$config->ai->dtable->prompts['actions']['type'] = 'actions';
$config->ai->dtable->prompts['actions']['list'] = $config->ai->actionList;
$config->ai->dtable->prompts['actions']['menu'] = $config->ai->actions->prompts;

$config->ai->dtable->miniPrograms = array();
$config->ai->dtable->miniPrograms['id']['title']    = 'ID';
$config->ai->dtable->miniPrograms['id']['type']     = 'id';
$config->ai->dtable->miniPrograms['id']['sortType'] = true;
$config->ai->dtable->miniPrograms['id']['required'] = true;

$config->ai->dtable->miniPrograms['name']['title']    = $lang->prompt->name;
$config->ai->dtable->miniPrograms['name']['width']    = '180';
$config->ai->dtable->miniPrograms['name']['required'] = true;
if($config->edition != 'open' && common::hasPriv('ai', 'miniProgramView')) $config->ai->dtable->miniPrograms['name']['link'] = array('module' => 'ai', 'method' => 'miniprogramview', 'params' => "id={id}");

$config->ai->dtable->miniPrograms['publishedLabel']['title']    = $lang->prompt->status;
$config->ai->dtable->miniPrograms['publishedLabel']['required'] = true;

$config->ai->dtable->miniPrograms['category']['title']    = $lang->prompt->module;
$config->ai->dtable->miniPrograms['category']['required'] = true;
$config->ai->dtable->miniPrograms['category']['map']      = $lang->ai->miniPrograms->categoryList;
$config->ai->dtable->miniPrograms['category']['hint']     = true;

$config->ai->dtable->miniPrograms['createdByLabel']['title']    = $lang->prompt->createdBy;
$config->ai->dtable->miniPrograms['createdByLabel']['required'] = true;

$config->ai->dtable->miniPrograms['createdDate']['title']    = $lang->prompt->createdDate;
$config->ai->dtable->miniPrograms['createdDate']['width']    = '130';
$config->ai->dtable->miniPrograms['createdDate']['sortType'] = true;
$config->ai->dtable->miniPrograms['createdDate']['required'] = true;

$config->ai->dtable->miniPrograms['publishedDate']['title']    = $lang->ai->miniPrograms->latestPublishedDate;
$config->ai->dtable->miniPrograms['publishedDate']['width']    = '130';
$config->ai->dtable->miniPrograms['publishedDate']['sortType'] = true;
$config->ai->dtable->miniPrograms['publishedDate']['required'] = true;

$config->ai->dtable->miniPrograms['actions']['type'] = 'actions';
$config->ai->dtable->miniPrograms['actions']['list'] = $config->ai->actionList;
$config->ai->dtable->miniPrograms['actions']['menu'] = $config->ai->actions->miniPrograms;
