<?php
$config->project->dtable = new stdclass();
$config->project->dtable->defaultField = array('id', 'name', 'status', 'PM', 'budget', 'begin', 'end', 'progress', 'actions');

$config->project->dtable->fieldList['id']['title']    = $lang->idAB;
$config->project->dtable->fieldList['id']['name']     = 'id';
$config->project->dtable->fieldList['id']['type']     = 'checkID';
$config->project->dtable->fieldList['id']['checkbox'] = true;
$config->project->dtable->fieldList['id']['group']    = 1;

$config->project->dtable->fieldList['name']['title']      = $lang->project->name;
$config->project->dtable->fieldList['name']['name']       = 'name';
$config->project->dtable->fieldList['name']['type']       = 'title';
$config->project->dtable->fieldList['name']['link']       = helper::createLink('project', 'index', 'projectID={id}');
$config->project->dtable->fieldList['name']['iconRender'] = 'RAWJS<function(val,row){ if(row.data.model == \'scrum\') return \'icon-sprint text-gray\'; if([\'waterfall\', \'kanban\', \'agileplus\', \'waterfallplus\'].indexOf(row.data.model) !== -1) return \'icon-\' + row.data.model + \' text-gray\'; return \'\';}>RAWJS';
$config->project->dtable->fieldList['name']['group']      = 1;

if(!empty($config->setCode))
{
    $config->project->dtable->fieldList['code']['title'] = $lang->project->code;
    $config->project->dtable->fieldList['code']['name']  = 'code';
    $config->project->dtable->fieldList['code']['type']  = 'text';
    $config->project->dtable->fieldList['code']['group'] = 1;
}

$config->project->dtable->fieldList['status']['title']     = $lang->project->status;
$config->project->dtable->fieldList['status']['name']      = 'status';
$config->project->dtable->fieldList['status']['type']      = 'status';
$config->project->dtable->fieldList['status']['statusMap'] = $lang->project->statusList;
$config->project->dtable->fieldList['status']['group']     = 2;

$config->project->dtable->fieldList['hasProduct']['title'] = $lang->project->type;
$config->project->dtable->fieldList['hasProduct']['name']  = 'hasProduct';
$config->project->dtable->fieldList['hasProduct']['type']  = 'category';
$config->project->dtable->fieldList['hasProduct']['group'] = 2;

$config->project->dtable->fieldList['PM']['title'] = $lang->project->PM;
$config->project->dtable->fieldList['PM']['name']  = 'PM';
$config->project->dtable->fieldList['PM']['type']  = 'avatarBtn';
$config->project->dtable->fieldList['PM']['group'] = 3;

$config->project->dtable->fieldList['budget']['title'] = $lang->project->budget;
$config->project->dtable->fieldList['budget']['name']  = 'budget';
$config->project->dtable->fieldList['budget']['type']  = 'money';
$config->project->dtable->fieldList['budget']['group'] = 4;

$config->project->dtable->fieldList['teamCount']['title'] = $lang->project->teamCount;
$config->project->dtable->fieldList['teamCount']['name']  = 'teamCount';
$config->project->dtable->fieldList['teamCount']['type']  = 'number';
$config->project->dtable->fieldList['teamCount']['group'] = 4;

$config->project->dtable->fieldList['invested']['title'] = $lang->project->invested;
$config->project->dtable->fieldList['invested']['name']  = 'invested';
$config->project->dtable->fieldList['invested']['type']  = 'count';
$config->project->dtable->fieldList['invested']['group'] = 4;

$config->project->dtable->fieldList['begin']['title'] = $lang->project->begin;
$config->project->dtable->fieldList['begin']['name']  = 'begin';
$config->project->dtable->fieldList['begin']['type']  = 'date';
$config->project->dtable->fieldList['begin']['group'] = 5;

$config->project->dtable->fieldList['end']['title'] = $lang->project->end;
$config->project->dtable->fieldList['end']['name']  = 'end';
$config->project->dtable->fieldList['end']['type']  = 'date';
$config->project->dtable->fieldList['end']['group'] = 5;

$config->project->dtable->fieldList['estimate']['title'] = $lang->project->estimate;
$config->project->dtable->fieldList['estimate']['name']  = 'estimate';
$config->project->dtable->fieldList['estimate']['type']  = 'number';
$config->project->dtable->fieldList['estimate']['group'] = 6;

$config->project->dtable->fieldList['consume']['title'] = $lang->project->consume;
$config->project->dtable->fieldList['consume']['name']  = 'consume';
$config->project->dtable->fieldList['consume']['type']  = 'number';
$config->project->dtable->fieldList['consume']['group'] = 6;

$config->project->dtable->fieldList['progress']['title'] = $lang->project->progress;
$config->project->dtable->fieldList['progress']['name']  = 'progress';
$config->project->dtable->fieldList['progress']['type']  = 'progress';
$config->project->dtable->fieldList['progress']['group'] = 6;

$config->project->dtable->fieldList['actions']['title'] = $lang->actions;
$config->project->dtable->fieldList['actions']['name']  = 'actions';
$config->project->dtable->fieldList['actions']['type']  = 'actions';

$config->project->dtable->fieldList['actions']['actionsMap']['start']['icon'] = 'play';
$config->project->dtable->fieldList['actions']['actionsMap']['start']['hint'] = $lang->project->start;
$config->project->dtable->fieldList['actions']['actionsMap']['start']['url']  = helper::createLink('project', 'start', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['close']['icon'] = 'off';
$config->project->dtable->fieldList['actions']['actionsMap']['close']['hint'] = $lang->project->close;
$config->project->dtable->fieldList['actions']['actionsMap']['close']['url']  = helper::createLink('project', 'close', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['active']['icon'] = 'magic';
$config->project->dtable->fieldList['actions']['actionsMap']['active']['hint'] = $lang->project->activate;
$config->project->dtable->fieldList['actions']['actionsMap']['active']['url']  = helper::createLink('project', 'activate', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['edit']['icon'] = 'edit';
$config->project->dtable->fieldList['actions']['actionsMap']['edit']['hint'] = $lang->project->edit;
$config->project->dtable->fieldList['actions']['actionsMap']['edit']['url']  = helper::createLink('project', 'edit', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['pause']['icon'] = 'pause';
$config->project->dtable->fieldList['actions']['actionsMap']['pause']['hint'] = $lang->project->suspend;
$config->project->dtable->fieldList['actions']['actionsMap']['pause']['url']  = helper::createLink('project', 'suspend', 'projectID={id}', '', true);

$config->project->dtable->fieldList['actions']['actionsMap']['group']['icon'] = 'group';
$config->project->dtable->fieldList['actions']['actionsMap']['group']['hint'] = $lang->project->team;
$config->project->dtable->fieldList['actions']['actionsMap']['group']['url']  = helper::createLink('project', 'team', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['perm']['icon'] = 'lock';
$config->project->dtable->fieldList['actions']['actionsMap']['perm']['hint'] = $lang->project->group;
$config->project->dtable->fieldList['actions']['actionsMap']['perm']['url']  = helper::createLink('project', 'group', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['link']['icon'] = 'link';
$config->project->dtable->fieldList['actions']['actionsMap']['link']['hint'] = $lang->project->manageProducts;
$config->project->dtable->fieldList['actions']['actionsMap']['link']['url']  = helper::createLink('project', 'manageProducts', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['icon'] = 'shield-check';
$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['hint'] = $lang->project->whitelist;
$config->project->dtable->fieldList['actions']['actionsMap']['whitelist']['url']  = helper::createLink('project', 'whitelist', 'projectID={id}');

$config->project->dtable->fieldList['actions']['actionsMap']['delete']['icon'] = 'trash';
$config->project->dtable->fieldList['actions']['actionsMap']['delete']['hint'] = $lang->project->delete;
$config->project->dtable->fieldList['actions']['actionsMap']['delete']['url']  = helper::createLink('project', 'delete', 'projectID={id}');
