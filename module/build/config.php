<?php
$config->build = new stdclass();
$config->build->create = new stdclass();
$config->build->edit   = new stdclass();
$config->build->create->requiredFields = 'execution,product,name,builder,date';
$config->build->edit->requiredFields   = 'execution,product,name,builder,date';

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

$config->build->actionList['linkStory']['icon']     = 'link';
$config->build->actionList['linkStory']['text']     = $lang->build->linkStory;
$config->build->actionList['linkStory']['hint']     = $lang->build->linkStory;
$config->build->actionList['linkStory']['url']      = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={id}&type=story&link=true');
$config->build->actionList['linkStory']['data-app'] = $app->tab;
$config->build->actionList['linkStory']['class']    = 'build-linkstory-btn';

$config->build->actionList['linkProjectStory']        = $config->build->actionList['linkStory'];
$config->build->actionList['linkProjectStory']['url'] = array('module' => 'projectbuild', 'method' => 'view', 'params' => 'buildID={id}&type=story&link=true');

$config->build->actionList['createTest']['icon']     = 'bullhorn';
$config->build->actionList['createTest']['text']     = $lang->build->createTest;
$config->build->actionList['createTest']['hint']     = $lang->build->createTest;
$config->build->actionList['createTest']['url']      = array('module' => 'testtask', 'method' => 'create', 'params' => 'product={product}&execution={execution}&build={id}&projectID={project}');
$config->build->actionList['createTest']['data-app'] = $app->tab;

$config->build->actionList['viewBug']['icon'] = 'bug';
$config->build->actionList['viewBug']['text'] = $lang->build->viewBug;
$config->build->actionList['viewBug']['hint'] = $lang->build->viewBug;
$config->build->actionList['viewBug']['url']  = array('module' => 'execution', 'method' => 'bug', 'params' => 'execution={execution}&productID={product}&branchID=all&orderBy=status&build={id}');

$config->build->actionList['bugList']         = $config->build->actionList['viewBug'];
$config->build->actionList['bugList']['text'] = $lang->build->bugList;
$config->build->actionList['bugList']['hint'] = $lang->build->bugList;
$config->build->actionList['bugList']['url']  = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={id}&type=generatedBug');

$config->build->actionList['projectBugList']        = $config->build->actionList['bugList'];
$config->build->actionList['projectBugList']['url'] = array('module' => 'projectbuild', 'method' => 'view', 'params' => 'buildID={id}&type=generatedBug');

$config->build->actionList['edit']['icon']     = 'edit';
$config->build->actionList['edit']['text']     = $lang->build->edit;
$config->build->actionList['edit']['hint']     = $lang->build->edit;
$config->build->actionList['edit']['url']      = array('module' => $app->tab == 'project' ? 'projectbuild' : 'build', 'method' => 'edit', 'params' => 'buildID={id}');
$config->build->actionList['edit']['data-app'] = $app->tab;

$config->build->actionList['delete']['icon']         = 'trash';
$config->build->actionList['delete']['text']         = $lang->build->delete;
$config->build->actionList['delete']['hint']         = $lang->build->delete;
$config->build->actionList['delete']['url']          = array('module' => ($app->tab == 'project' ? 'projectbuild' : 'build'), 'method' => 'delete', 'params' => 'buildID={id}');
$config->build->actionList['delete']['className']    = 'ajax-submit';
$config->build->actionList['delete']['data-confirm'] = array('message' => $lang->build->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->build->actionList['unlinkBug']['icon']         = 'unlink';
$config->build->actionList['unlinkBug']['text']         = $lang->build->unlinkBug;
$config->build->actionList['unlinkBug']['hint']         = $lang->build->unlinkBug;
$config->build->actionList['unlinkBug']['url']          = '';
$config->build->actionList['unlinkBug']['className']    = 'ajax-submit';
$config->build->actionList['unlinkBug']['data-confirm'] = $lang->build->confirmUnlinkBug;

$config->build->actionList['unlinkStory']['icon']         = 'unlink';
$config->build->actionList['unlinkStory']['text']         = $lang->build->unlinkStory;
$config->build->actionList['unlinkStory']['hint']         = $lang->build->unlinkStory;
$config->build->actionList['unlinkStory']['url']          = '';
$config->build->actionList['unlinkStory']['className']    = 'ajax-submit';
$config->build->actionList['unlinkStory']['data-confirm'] = $lang->build->confirmUnlinkStory;

$config->build->actions = new stdclass();
$config->build->actions->view = array();
$config->build->actions->view['mainActions']   = array('edit', 'delete');
$config->build->actions->view['suffixActions'] = array();
