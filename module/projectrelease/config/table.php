<?php
global $app, $lang;
$app->loadLang('release');
$app->loadModuleConfig('release');

$config->projectrelease->actionList = array();

$config->projectrelease->actionList['linkStory']['icon'] = 'link';
$config->projectrelease->actionList['linkStory']['hint'] = $lang->release->linkStory;
$config->projectrelease->actionList['linkStory']['url']  = helper::createLink('projectrelease', 'view', 'releaseID={id}&type=story&link=true');

$config->projectrelease->actionList['linkBug']['icon'] = 'bug';
$config->projectrelease->actionList['linkBug']['hint'] = $lang->release->linkBug;
$config->projectrelease->actionList['linkBug']['url']  = helper::createLink('projectrelease', 'view', 'releaseID={id}&type=bug&link=true');

$config->projectrelease->actionList['play']['icon']         = 'play';
$config->projectrelease->actionList['play']['hint']         = $lang->release->changeStatusList['normal'];
$config->projectrelease->actionList['play']['url']          = helper::createLink('projectrelease', 'changeStatus', 'releaseID={id}&status=normal');
$config->projectrelease->actionList['play']['className']    = 'ajax-submit';
$config->projectrelease->actionList['play']['data-confirm'] = array('message' => $lang->release->confirmActivate, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->projectrelease->actionList['pause']['icon']         = 'pause';
$config->projectrelease->actionList['pause']['hint']         = $lang->release->changeStatusList['terminate'];
$config->projectrelease->actionList['pause']['url']          = helper::createLink('projectrelease', 'changeStatus', 'releaseID={id}&status=terminate');
$config->projectrelease->actionList['pause']['className']    = 'ajax-submit';
$config->projectrelease->actionList['pause']['data-confirm'] = array('message' => $lang->release->confirmTerminate, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->projectrelease->actionList['edit']['icon'] = 'edit';
$config->projectrelease->actionList['edit']['hint'] = $lang->release->edit;
$config->projectrelease->actionList['edit']['url']  = helper::createLink('projectrelease', 'edit', 'releaseID={id}');

$config->projectrelease->actionList['notify']['icon']        = 'bullhorn';
$config->projectrelease->actionList['notify']['hint']        = $lang->release->notify;
$config->projectrelease->actionList['notify']['url']         = helper::createLink('projectrelease', 'notify', 'releaseID={id}', '', true);
$config->projectrelease->actionList['notify']['data-toggle'] = 'modal';

$config->projectrelease->actionList['delete']['icon']         = 'trash';
$config->projectrelease->actionList['delete']['hint']         = $lang->release->delete;
$config->projectrelease->actionList['delete']['url']          = helper::createLink('projectrelease', 'delete', 'releaseID={id}');
$config->projectrelease->actionList['delete']['class']        = 'btn ghost toolbar-item text-primary square size-sm ajax-submit';
$config->projectrelease->actionList['delete']['data-confirm'] = array('message' => $lang->release->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->projectrelease->dtable = new stdclass();
$config->projectrelease->dtable->fieldList['id']['name']  = 'id';
$config->projectrelease->dtable->fieldList['id']['title'] = $lang->idAB;
$config->projectrelease->dtable->fieldList['id']['type']  = 'id';
$config->projectrelease->dtable->fieldList['id']['fixed'] = 'left';

$config->projectrelease->dtable->fieldList['name']['name']  = 'name';
$config->projectrelease->dtable->fieldList['name']['title'] = $lang->release->name;
$config->projectrelease->dtable->fieldList['name']['type']  = 'text';
$config->projectrelease->dtable->fieldList['name']['fixed'] = 'left';
$config->projectrelease->dtable->fieldList['name']['link']  = array('module' => 'projectrelease', 'method' => 'view', 'params' => 'releaseID={id}');

$config->projectrelease->dtable->fieldList['product']['name']  = 'product';
$config->projectrelease->dtable->fieldList['product']['title'] = $lang->projectrelease->product;
$config->projectrelease->dtable->fieldList['product']['type']  = 'text';
$config->projectrelease->dtable->fieldList['product']['group'] = '1';

$config->projectrelease->dtable->fieldList['branch']['title'] = $lang->release->branch;
$config->projectrelease->dtable->fieldList['branch']['name']  = 'branch';
$config->projectrelease->dtable->fieldList['branch']['type']  = 'text';
$config->projectrelease->dtable->fieldList['branch']['group'] = '1';

$config->projectrelease->dtable->fieldList['build']['name']  = 'build';
$config->projectrelease->dtable->fieldList['build']['title'] = $lang->release->includedBuild;
$config->projectrelease->dtable->fieldList['build']['type']  = 'text';
$config->projectrelease->dtable->fieldList['build']['group'] = '1';

$config->projectrelease->dtable->fieldList['status']['title']     = $lang->release->status;
$config->projectrelease->dtable->fieldList['status']['name']      = 'status';
$config->projectrelease->dtable->fieldList['status']['type']      = 'status';
$config->projectrelease->dtable->fieldList['status']['statusMap'] = $lang->release->statusList;
$config->projectrelease->dtable->fieldList['status']['group']     = '2';

$config->projectrelease->dtable->fieldList['date']['title'] = $lang->release->date;
$config->projectrelease->dtable->fieldList['date']['name']  = 'date';
$config->projectrelease->dtable->fieldList['date']['type']  = 'date';
$config->projectrelease->dtable->fieldList['date']['group'] = '3';

$config->projectrelease->dtable->fieldList['actions']['title'] = $lang->actions;
$config->projectrelease->dtable->fieldList['actions']['name']  = 'actions';
$config->projectrelease->dtable->fieldList['actions']['type']  = 'actions';
$config->projectrelease->dtable->fieldList['actions']['list']  = $config->projectrelease->actionList;
$config->projectrelease->dtable->fieldList['actions']['menu']  = array('linkStory', 'linkBug', 'play|pause', 'edit', 'notify', 'delete');
