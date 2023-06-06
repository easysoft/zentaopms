<?php
$config->release = new stdclass();
$config->release->create = new stdclass();
$config->release->edit   = new stdclass();
$config->release->create->requiredFields = 'name,date';
$config->release->edit->requiredFields   = 'name,date';

$config->release->editor = new stdclass();
$config->release->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->release->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

global $lang;
$config->release->actionList['linkStory']['icon'] = 'link';
$config->release->actionList['linkStory']['hint'] = $lang->release->linkStory;
$config->release->actionList['linkStory']['url']  = helper::createLink('release', 'view', 'releaseID={id}&type=story&link=true');

$config->release->actionList['linkBug']['icon'] = 'bug';
$config->release->actionList['linkBug']['hint'] = $lang->release->linkBug;
$config->release->actionList['linkBug']['url']  = helper::createLink('release', 'view', 'releaseID={id}&type=bug&link=true');

$config->release->actionList['play']['icon'] = 'play';
$config->release->actionList['play']['hint'] = $this->lang->release->changeStatusList['normal'];
$config->release->actionList['play']['url']  = helper::createLink('release', 'changeStatus', 'releaseID={id}&status=normal');

$config->release->actionList['pause']['icon'] = 'pause';
$config->release->actionList['pause']['hint'] = $this->lang->release->changeStatusList['terminate'];
$config->release->actionList['pause']['url']  = helper::createLink('release', 'changeStatus', 'releaseID={id}&status=terminate');

$config->release->actionList['edit']['icon'] = 'edit';
$config->release->actionList['edit']['hint'] = $lang->release->edit;
$config->release->actionList['edit']['url']  = helper::createLink('release', 'edit', 'releaseID={id}');

$config->release->actionList['notify']['icon']        = 'bullhorn';
$config->release->actionList['notify']['hint']        = $lang->release->notify;
$config->release->actionList['notify']['url']         = helper::createLink('release', 'notify', 'releaseID={id}', '', true);
$config->release->actionList['notify']['data-toggle'] = 'modal';

$config->release->actionList['delete']['icon'] = 'trash';
$config->release->actionList['delete']['hint'] = $lang->release->delete;
$config->release->actionList['delete']['url']  = 'javascript:confirmDelete("{id}")';
