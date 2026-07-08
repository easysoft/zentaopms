<?php
$config->my->execution = new stdclass();
$config->my->execution->dtable = new stdclass();
$config->my->execution->dtable->fieldList['id']['name']  = 'id';
$config->my->execution->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->execution->dtable->fieldList['id']['type']  = 'id';
$config->my->execution->dtable->fieldList['id']['group'] = '1';

$config->my->execution->dtable->fieldList['name']['name']         = 'name';
$config->my->execution->dtable->fieldList['name']['title']        = $lang->execution->name;
$config->my->execution->dtable->fieldList['name']['type']         = 'nestedTitle';
$config->my->execution->dtable->fieldList['name']['link']         = 'RAWJS<function(info){ if(info.row.data.isParent) return false; else return \'' . helper::createLink('execution', 'browse', 'id={id}&from=my') . '\'; }>RAWJS';
$config->my->execution->dtable->fieldList['name']['fixed']        = 'left';
$config->my->execution->dtable->fieldList['name']['group']        = '1';
$config->my->execution->dtable->fieldList['name']['nestedToggle'] = true;
$config->my->execution->dtable->fieldList['name']['show']         = true;

$config->my->execution->dtable->fieldList['code']['name']   = 'code';
$config->my->execution->dtable->fieldList['code']['title']  = $lang->execution->code;
$config->my->execution->dtable->fieldList['code']['type']   = 'text';
$config->my->execution->dtable->fieldList['code']['fixed']  = 'left';
$config->my->execution->dtable->fieldList['code']['group']  = '1';
$config->my->execution->dtable->fieldList['code']['show']   = false;

$config->my->execution->dtable->fieldList['projectName']['name']   = 'projectName';
$config->my->execution->dtable->fieldList['projectName']['title']  = $lang->execution->project;
$config->my->execution->dtable->fieldList['projectName']['type']   = 'text';
$config->my->execution->dtable->fieldList['projectName']['link']   = array('module' => 'project', 'method' => 'view', 'params' => 'id={project}');
$config->my->execution->dtable->fieldList['projectName']['group']  = '2';
$config->my->execution->dtable->fieldList['projectName']['show']   = false;

$config->my->execution->dtable->fieldList['status']['name']      = 'status';
$config->my->execution->dtable->fieldList['status']['title']     = $lang->execution->status;
$config->my->execution->dtable->fieldList['status']['type']      = 'status';
$config->my->execution->dtable->fieldList['status']['statusMap'] = $lang->execution->statusList;
$config->my->execution->dtable->fieldList['status']['group']     = '2';
$config->my->execution->dtable->fieldList['status']['show']      = true;
if($isEn) $config->my->execution->dtable->fieldList['status']['width'] = '130';

$config->my->execution->dtable->fieldList['PM']['name']   = 'PM';
$config->my->execution->dtable->fieldList['PM']['title']  = $lang->execution->PM;
$config->my->execution->dtable->fieldList['PM']['type']   = 'avatarBtn';
$config->my->execution->dtable->fieldList['PM']['group']  = '2';
$config->my->execution->dtable->fieldList['PM']['show']   = false;

$config->my->execution->dtable->fieldList['role']['name']  = 'role';
$config->my->execution->dtable->fieldList['role']['title'] = $lang->team->roleAB;
$config->my->execution->dtable->fieldList['role']['type']  = 'category';
$config->my->execution->dtable->fieldList['role']['group'] = '2';
$config->my->execution->dtable->fieldList['role']['show']  = true;

$config->my->execution->dtable->fieldList['assignedToMeTasks']['name']     = 'assignedToMeTasks';
$config->my->execution->dtable->fieldList['assignedToMeTasks']['title']    = $lang->execution->myTask;
$config->my->execution->dtable->fieldList['assignedToMeTasks']['type']     = 'count';
$config->my->execution->dtable->fieldList['assignedToMeTasks']['group']    = '2';
$config->my->execution->dtable->fieldList['assignedToMeTasks']['sortType'] = false;
$config->my->execution->dtable->fieldList['assignedToMeTasks']['show']     = true;

$config->my->execution->dtable->fieldList['openedDate']['name']   = 'openedDate';
$config->my->execution->dtable->fieldList['openedDate']['title']  = $lang->execution->openedDate;
$config->my->execution->dtable->fieldList['openedDate']['type']   = 'date';
$config->my->execution->dtable->fieldList['openedDate']['group']  = '3';
$config->my->execution->dtable->fieldList['openedDate']['show']   = false;

$config->my->execution->dtable->fieldList['begin']['name']  = 'begin';
$config->my->execution->dtable->fieldList['begin']['title'] = $lang->execution->begin;
$config->my->execution->dtable->fieldList['begin']['type']  = 'date';
$config->my->execution->dtable->fieldList['begin']['group'] = '3';
$config->my->execution->dtable->fieldList['begin']['show']  = true;
if($isEn) $config->my->execution->dtable->fieldList['begin']['width'] = '120';

$config->my->execution->dtable->fieldList['end']['name']  = 'end';
$config->my->execution->dtable->fieldList['end']['title'] = $lang->execution->end;
$config->my->execution->dtable->fieldList['end']['type']  = 'date';
$config->my->execution->dtable->fieldList['end']['group'] = '3';
$config->my->execution->dtable->fieldList['end']['show']  = true;
if($isEn) $config->my->execution->dtable->fieldList['end']['width'] = '120';

$config->my->execution->dtable->fieldList['join']['name']     = 'join';
$config->my->execution->dtable->fieldList['join']['title']    = $lang->team->join;
$config->my->execution->dtable->fieldList['join']['type']     = 'date';
$config->my->execution->dtable->fieldList['join']['group']    = '4';
$config->my->execution->dtable->fieldList['join']['sortType'] = false;
$config->my->execution->dtable->fieldList['join']['show']     = true;

$config->my->execution->dtable->fieldList['hours']['name']     = 'hours';
$config->my->execution->dtable->fieldList['hours']['title']    = $lang->my->hours;
$config->my->execution->dtable->fieldList['hours']['type']     = 'number';
$config->my->execution->dtable->fieldList['hours']['group']    = '4';
$config->my->execution->dtable->fieldList['hours']['sortType'] = false;
$config->my->execution->dtable->fieldList['hours']['show']     = true;

$config->my->execution->dtable->fieldList['realBegan']['name']   = 'realBegan';
$config->my->execution->dtable->fieldList['realBegan']['title']  = $lang->execution->realBegan;
$config->my->execution->dtable->fieldList['realBegan']['type']   = 'date';
$config->my->execution->dtable->fieldList['realBegan']['group']  = '4';
$config->my->execution->dtable->fieldList['realBegan']['show']   = false;

$config->my->execution->dtable->fieldList['realEnd']['name']   = 'realEnd';
$config->my->execution->dtable->fieldList['realEnd']['title']  = $lang->execution->realEnd;
$config->my->execution->dtable->fieldList['realEnd']['type']   = 'date';
$config->my->execution->dtable->fieldList['realEnd']['group']  = '4';
$config->my->execution->dtable->fieldList['realEnd']['show']   = false;

$config->my->execution->dtable->fieldList['estimate']['title']    = $lang->execution->totalEstimate;
$config->my->execution->dtable->fieldList['estimate']['name']     = 'totalEstimate';
$config->my->execution->dtable->fieldList['estimate']['type']     = 'number';
$config->my->execution->dtable->fieldList['estimate']['sortType'] = false;
$config->my->execution->dtable->fieldList['estimate']['group']    = '4';
$config->my->execution->dtable->fieldList['estimate']['show']     = false;

$config->my->execution->dtable->fieldList['consumed']['title']    = $lang->execution->totalConsumed;
$config->my->execution->dtable->fieldList['consumed']['name']     = 'totalConsumed';
$config->my->execution->dtable->fieldList['consumed']['type']     = 'number';
$config->my->execution->dtable->fieldList['consumed']['sortType'] = false;
$config->my->execution->dtable->fieldList['consumed']['group']    = '4';
$config->my->execution->dtable->fieldList['consumed']['show']     = false;

$config->my->execution->dtable->fieldList['left']['title']    = $lang->execution->totalLeft;
$config->my->execution->dtable->fieldList['left']['name']     = 'totalLeft';
$config->my->execution->dtable->fieldList['left']['type']     = 'number';
$config->my->execution->dtable->fieldList['left']['sortType'] = false;
$config->my->execution->dtable->fieldList['left']['width']    = '64';
$config->my->execution->dtable->fieldList['left']['group']    = '4';
$config->my->execution->dtable->fieldList['left']['show']     = false;

$config->my->execution->dtable->fieldList['progress']['title']    = $lang->execution->progress;
$config->my->execution->dtable->fieldList['progress']['name']     = 'progress';
$config->my->execution->dtable->fieldList['progress']['type']     = 'progress';
$config->my->execution->dtable->fieldList['progress']['sortType'] = false;
$config->my->execution->dtable->fieldList['progress']['group']    = '4';
$config->my->execution->dtable->fieldList['progress']['show']     = true;
if($isEn) $config->my->execution->dtable->fieldList['progress']['width'] = '130';

$config->my->execution->dtable->fieldList['burn']['title']    = $lang->execution->burn;
$config->my->execution->dtable->fieldList['burn']['name']     = 'burn';
$config->my->execution->dtable->fieldList['burn']['type']     = 'burn';
$config->my->execution->dtable->fieldList['burn']['sortType'] = false;
$config->my->execution->dtable->fieldList['burn']['group']    = '4';
$config->my->execution->dtable->fieldList['burn']['show']     = false;

$config->my->execution->dtable->fieldList['subStatus']['name']   = 'subStatus';
$config->my->execution->dtable->fieldList['subStatus']['title']  = $lang->execution->subStatus;
$config->my->execution->dtable->fieldList['subStatus']['type']   = 'text';
$config->my->execution->dtable->fieldList['subStatus']['group']  = '5';
$config->my->execution->dtable->fieldList['subStatus']['show']   = false;
