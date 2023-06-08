<?php
$config->my = new stdclass();
$config->my->editprofile = new stdclass();
$config->my->editprofile->requiredFields = 'account,realname';

$config->my->dynamicCounts = 14;
$config->my->todoCounts    = 10;
$config->my->taskCounts    = 10;
$config->my->bugCounts     = 10;
$config->my->storyCounts   = 10;

$config->my->oaObjectType = 'attend,leave,makeup,overtime,lieu';

$config->mobile = new stdclass();
$config->mobile->todoBar  = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'all');
$config->mobile->taskBar  = array('assignedTo', 'openedBy');
$config->mobile->bugBar   = array('assignedTo', 'openedBy', 'resolvedBy');
$config->mobile->storyBar = array('assignedTo', 'openedBy', 'reviewedBy');

global $lang,$app;
$config->my->audit = new stdclass();
$config->my->audit->actionList = array();
$config->my->audit->actionList['review']['icon']        = 'glasses';
$config->my->audit->actionList['review']['text']        = $lang->review->common;
$config->my->audit->actionList['review']['hint']        = $lang->review->common;
$config->my->audit->actionList['review']['data-toggle'] = 'modal';

$app->loadLang('project');
$config->my->project = new stdclass();
$config->my->project->actionList = array();

$config->my->project->actionList['start']['icon'] = 'play';
$config->my->project->actionList['start']['hint'] = $lang->project->start;
$config->my->project->actionList['start']['url']  = helper::createLink('project', 'start', 'projectID={id}', '', true);

$config->my->project->actionList['close']['icon'] = 'off';
$config->my->project->actionList['close']['hint'] = $lang->project->close;
$config->my->project->actionList['close']['url']  = helper::createLink('project', 'close', 'projectID={id}', '', true);

$config->my->project->actionList['active']['icon'] = 'magic';
$config->my->project->actionList['active']['hint'] = $lang->project->activate;
$config->my->project->actionList['active']['url']  = helper::createLink('project', 'activate', 'projectID={id}', '', true);

$config->my->project->actionList['edit']['icon'] = 'edit';
$config->my->project->actionList['edit']['hint'] = $lang->project->edit;
$config->my->project->actionList['edit']['url']  = helper::createLink('project', 'edit', 'projectID={id}');

$config->my->project->actionList['pause']['icon'] = 'pause';
$config->my->project->actionList['pause']['hint'] = $lang->project->suspend;
$config->my->project->actionList['pause']['url']  = helper::createLink('project', 'suspend', 'projectID={id}', '', true);

$config->my->project->actionList['group']['icon'] = 'group';
$config->my->project->actionList['group']['hint'] = $lang->project->team;
$config->my->project->actionList['group']['url']  = helper::createLink('project', 'team', 'projectID={id}');

$config->my->project->actionList['perm']['icon'] = 'lock';
$config->my->project->actionList['perm']['hint'] = $lang->project->group;
$config->my->project->actionList['perm']['url']  = helper::createLink('project', 'group', 'projectID={id}');

$config->my->project->actionList['link']['icon'] = 'link';
$config->my->project->actionList['link']['hint'] = $lang->project->manageProducts;
$config->my->project->actionList['link']['url']  = helper::createLink('project', 'manageProducts', 'projectID={id}');

$config->my->project->actionList['whitelist']['icon'] = 'shield-check';
$config->my->project->actionList['whitelist']['hint'] = $lang->project->whitelist;
$config->my->project->actionList['whitelist']['url']  = helper::createLink('project', 'whitelist', 'projectID={id}');

$config->my->project->actionList['delete']['icon'] = 'trash';
$config->my->project->actionList['delete']['hint'] = $lang->project->delete;
$config->my->project->actionList['delete']['url']  = helper::createLink('project', 'delete', 'projectID={id}');
