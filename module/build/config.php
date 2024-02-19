<?php
$config->build = new stdclass();
$config->build->create = new stdclass();
$config->build->edit   = new stdclass();
$config->build->create->requiredFields = 'product,name,builder,date';
$config->build->edit->requiredFields   = 'product,name,builder,date';

$config->build->editor = new stdclass();
$config->build->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->build->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

global $lang, $app;
$config->build->search['module']             = 'build';
$config->build->search['fields']['name']     = $lang->build->nameAB;
$config->build->search['fields']['id']       = $lang->build->id;
$config->build->search['fields']['product']  = $lang->build->product;
$config->build->search['fields']['scmPath']  = $lang->build->scmPath;
$config->build->search['fields']['filePath'] = $lang->build->filePath;
$config->build->search['fields']['date']     = $lang->build->date;
$config->build->search['fields']['builder']  = $lang->build->builder;
$config->build->search['fields']['desc']     = $lang->build->desc;

$config->build->search['params']['name']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->build->search['params']['product']  = array('operator' => '=',       'control' => 'select', 'values' => 'products');
$config->build->search['params']['scmPath']  = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->build->search['params']['filePath'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->build->search['params']['date']     = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->build->search['params']['builder']  = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->build->search['params']['desc']     = array('operator' => 'include', 'control' => 'input',  'values' => '');

$config->build->actionList['linkStory']['icon'] = 'link';
$config->build->actionList['linkStory']['hint'] = $lang->build->linkStory;
$config->build->actionList['linkStory']['url']  = helper::createLink('build', 'view', 'buildID={id}&type=story&link=true');

$config->build->actionList['linkProjectStory'] = $config->build->actionList['linkStory'];
$config->build->actionList['linkProjectStory']['url'] = helper::createLink('projectbuild', 'view', 'buildID={id}&type=story&link=true');

$config->build->actionList['createTest']['icon']     = 'bullhorn';
$config->build->actionList['createTest']['hint']     = $lang->build->createTest;
$config->build->actionList['createTest']['url']      = helper::createLink('testtask', 'create', 'product={product}&execution={execution}&build={id}&projectID={project}');
$config->build->actionList['createTest']['data-app'] = $app->tab;

$config->build->actionList['viewBug']['icon'] = 'bug';
$config->build->actionList['viewBug']['hint'] = $lang->build->viewBug;
$config->build->actionList['viewBug']['url']  = helper::createLink('execution', 'bug', 'execution={execution}&productID={product}&branchID=all&orderBy=status&build={id}');

$config->build->actionList['bugList'] = $config->build->actionList['viewBug'];
$config->build->actionList['bugList']['hint'] = $lang->build->bugList;
$config->build->actionList['bugList']['url']  = helper::createLink('build', 'view', 'buildID={id}&type=generatedBug');

$config->build->actionList['projectBugList'] = $config->build->actionList['bugList'];
$config->build->actionList['projectBugList']['url']  = helper::createLink('projectbuild', 'view', 'buildID={id}&type=generatedBug');

$config->build->actionList['buildEdit']['icon'] = 'edit';
$config->build->actionList['buildEdit']['hint'] = $lang->build->edit;
$config->build->actionList['buildEdit']['url']  = helper::createLink('build', 'edit', 'buildID={id}');

$config->build->actionList['projectbuildEdit'] = $config->build->actionList['buildEdit'];
$config->build->actionList['projectbuildEdit']['url'] = helper::createLink('projectbuild', 'edit', 'buildID={id}');

$config->build->actionList['delete']['icon']         = 'trash';
$config->build->actionList['delete']['hint']         = $lang->build->delete;
$config->build->actionList['delete']['url']          = helper::createLink($app->tab == 'project' ? 'projectbuild' : 'build', 'delete', 'buildID={id}');
$config->build->actionList['delete']['className']    = 'ajax-submit';
$config->build->actionList['delete']['data-confirm'] = array('message' => $lang->build->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->build->actionList['unlinkBug']['icon']         = 'unlink';
$config->build->actionList['unlinkBug']['hint']         = $lang->build->unlinkBug;
$config->build->actionList['unlinkBug']['url']          = '';
$config->build->actionList['unlinkBug']['className']    = 'ajax-submit';
$config->build->actionList['unlinkBug']['data-confirm'] = $lang->build->confirmUnlinkBug;

$config->build->actionList['unlinkStory']['icon']         = 'unlink';
$config->build->actionList['unlinkStory']['hint']         = $lang->build->unlinkStory;
$config->build->actionList['unlinkStory']['url']          = '';
$config->build->actionList['unlinkStory']['className']    = 'ajax-submit';
$config->build->actionList['unlinkStory']['data-confirm'] = $lang->build->confirmUnlinkStory;
