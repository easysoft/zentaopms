<?php
$config->release = new stdclass();
$config->release->create  = new stdclass();
$config->release->edit    = new stdclass();
$config->release->publish = new stdclass();
$config->release->create->requiredFields  = 'name,date,releasedDate';
$config->release->edit->requiredFields    = 'name,date,releasedDate';
$config->release->publish->requiredFields = 'releasedDate';

$config->release->editor = new stdclass();
$config->release->editor->create  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->release->editor->edit    = array('id' => 'desc', 'tools' => 'simpleTools');

global $lang, $app;
$config->release->actionList['linkStory']['icon'] = 'link';
$config->release->actionList['linkStory']['hint'] = $lang->release->linkStory;
$config->release->actionList['linkStory']['url']  = array('module' => 'release', 'method' => 'view', 'params' => 'releaseID={id}&type=story&link=true');

$config->release->actionList['unlinkStory']['icon'] = 'unlink';
$config->release->actionList['unlinkStory']['hint'] = $lang->release->unlinkStory;
$config->release->actionList['unlinkStory']['url']  = 'javascript: unlinkObject("story", "{id}")';

$config->release->actionList['linkBug']['icon'] = 'bug';
$config->release->actionList['linkBug']['hint'] = $lang->release->linkBug;
$config->release->actionList['linkBug']['url']  = array('module' => 'release', 'method' => 'view', 'params' => 'releaseID={id}&type=bug&link=true');

$config->release->actionList['unlinkBug']['icon'] = 'unlink';
$config->release->actionList['unlinkBug']['hint'] = $lang->release->unlinkBug;
$config->release->actionList['unlinkBug']['url']  = 'javascript: unlinkObject("bug", "{id}")';

$config->release->actionList['unlinkLeftBug']['icon'] = 'unlink';
$config->release->actionList['unlinkLeftBug']['hint'] = $lang->release->unlinkBug;
$config->release->actionList['unlinkLeftBug']['url']  = 'javascript: unlinkObject("leftBug", "{id}")';

$config->release->actionList['publish']['icon']         = 'publish';
$config->release->actionList['publish']['text ']        = $this->lang->release->changeStatusList['wait'];
$config->release->actionList['publish']['hint']         = $this->lang->release->changeStatusList['wait'];
$config->release->actionList['publish']['url']          = array('module' => $app->tab == 'project' ? 'projectrelease' : 'release', 'method' => 'publish', 'params' => 'releaseID={id}');
$config->release->actionList['publish']['notLoadModel'] = true;
$config->release->actionList['publish']['data-toggle']  = 'modal';
$config->release->actionList['publish']['class']        = 'publish-btn';

$config->release->actionList['play']['icon']         = 'play';
$config->release->actionList['play']['text ']        = $this->lang->release->changeStatusList['normal'];
$config->release->actionList['play']['hint']         = $this->lang->release->changeStatusList['normal'];
$config->release->actionList['play']['url']          = array('module' => $app->tab == 'project' ? 'projectrelease' : 'release', 'method' => 'changeStatus', 'params' => 'releaseID={id}&action=active');
$config->release->actionList['play']['notLoadModel'] = true;
$config->release->actionList['play']['className']    = 'ajax-submit';
$config->release->actionList['play']['data-confirm'] = array('message' => $lang->release->confirmActivate, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->release->actionList['pause']['icon']         = 'pause';
$config->release->actionList['pause']['text']         = $this->lang->release->changeStatusList['terminate'];
$config->release->actionList['pause']['hint']         = $this->lang->release->changeStatusList['terminate'];
$config->release->actionList['pause']['url']          = array('module' => $app->tab == 'project' ? 'projectrelease' : 'release', 'method' => 'changeStatus', 'params' => 'releaseID={id}&action=pause');
$config->release->actionList['pause']['notLoadModel'] = true;
$config->release->actionList['pause']['className']    = 'ajax-submit';
$config->release->actionList['pause']['data-confirm'] = array('message' => $lang->release->confirmTerminate, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->release->actionList['edit']['icon'] = 'edit';
$config->release->actionList['edit']['text'] = $lang->release->edit;
$config->release->actionList['edit']['hint'] = $lang->release->edit;
$config->release->actionList['edit']['url']  = helper::createLink('release', 'edit', 'releaseID={id}');

$config->release->actionList['notify']['icon']        = 'bullhorn';
$config->release->actionList['notify']['hint']        = $lang->release->notify;
$config->release->actionList['notify']['url']         = helper::createLink('release', 'notify', 'releaseID={id}');
$config->release->actionList['notify']['data-toggle'] = 'modal';

$config->release->actionList['delete']['icon']         = 'trash';
$config->release->actionList['delete']['text']         = $lang->release->delete;
$config->release->actionList['delete']['hint']         = $lang->release->delete;
$config->release->actionList['delete']['url']          = helper::createLink($app->tab == 'project' ? 'projectrelease' : 'release', 'delete', 'releaseID={id}');
$config->release->actionList['delete']['className']    = 'ajax-submit';
$config->release->actionList['delete']['data-confirm'] = array('message' => $lang->release->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

/* Search config. */
$config->release->search['module']            = 'release';
$config->release->search['fields']['name']    = $lang->release->name;
$config->release->search['fields']['branch']  = $lang->release->branch;
$config->release->search['fields']['id']      = $lang->idAB;
$config->release->search['fields']['build']   = $lang->release->includedBuild;
$config->release->search['fields']['status']  = $lang->release->status;
$config->release->search['fields']['date']    = $lang->release->date;
$config->release->search['fields']['marker']  = $lang->release->marker;

$config->release->search['params']['name']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->release->search['params']['branch']  = array('operator' => '=',       'control' => 'select', 'values' => array());
$config->release->search['params']['id']      = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->release->search['params']['build']   = array('operator' => 'include', 'control' => 'select', 'values' => array());
$config->release->search['params']['status']  = array('operator' => '=',       'control' => 'select', 'values' => $lang->release->statusList);
$config->release->search['params']['date']    = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->release->search['params']['marker']  = array('operator' => '=',       'control' => 'select', 'values' => $lang->release->markerList);

$config->release->actions = new stdclass();
$config->release->actions->view = array();
$config->release->actions->view['mainActions']   = array('publish', 'play', 'pause');
$config->release->actions->view['suffixActions'] = array('edit', 'delete');
