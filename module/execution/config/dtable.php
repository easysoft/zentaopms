<?php

$config->execution->dtable = new stdclass();
$config->execution->dtable->team = new stdclass();

$config->execution->dtable->fieldList['rawID']['title']    = $lang->idAB;
$config->execution->dtable->fieldList['rawID']['name']     = 'rawID';
$config->execution->dtable->fieldList['rawID']['type']     = 'checkID';
$config->execution->dtable->fieldList['rawID']['sortType'] = 'desc';
$config->execution->dtable->fieldList['rawID']['checkbox'] = true;
$config->execution->dtable->fieldList['rawID']['width']    = '80';
$config->execution->dtable->fieldList['rawID']['required'] = true;

$config->execution->dtable->fieldList['name']['title']        = $lang->execution->name;
$config->execution->dtable->fieldList['name']['name']         = 'name';
$config->execution->dtable->fieldList['name']['fixed']        = 'left';
$config->execution->dtable->fieldList['name']['flex']         = 1;
$config->execution->dtable->fieldList['name']['type']         = 'html';
$config->execution->dtable->fieldList['name']['nestedToggle'] = true;
$config->execution->dtable->fieldList['name']['sortType']     = true;
$config->execution->dtable->fieldList['name']['minWidth']     = '356';
$config->execution->dtable->fieldList['name']['required']     = true;

if(isset($config->setCode) and $config->setCode == 1)
{
    $config->execution->dtable->fieldList['code']['title']    = $lang->execution->code;
    $config->execution->dtable->fieldList['code']['name']     = 'code';
    $config->execution->dtable->fieldList['code']['fixed']    = 'left';
    $config->execution->dtable->fieldList['code']['type']     = 'text';
    $config->execution->dtable->fieldList['code']['sortType'] = true;
    $config->execution->dtable->fieldList['code']['width']    = '136';
    $config->execution->dtable->fieldList['code']['show']     = 'true';
}

$config->execution->dtable->fieldList['project']['title']    = $lang->execution->project;
$config->execution->dtable->fieldList['project']['name']     = 'project';
$config->execution->dtable->fieldList['project']['type']     = 'desc';
$config->execution->dtable->fieldList['project']['sortType'] = true;
$config->execution->dtable->fieldList['project']['width']    = '160';
$config->execution->dtable->fieldList['project']['group']    = '1';
$config->execution->dtable->fieldList['project']['show']     = true;

$config->execution->dtable->fieldList['status']['title']     = $lang->execution->status;
$config->execution->dtable->fieldList['status']['name']      = 'status';
$config->execution->dtable->fieldList['status']['type']      = 'status';
$config->execution->dtable->fieldList['status']['statusMap'] = $lang->execution->statusList;
$config->execution->dtable->fieldList['status']['sortType']  = true;
$config->execution->dtable->fieldList['status']['width']     = '80';
$config->execution->dtable->fieldList['status']['group']     = '1';
$config->execution->dtable->fieldList['status']['show']      = true;

$config->execution->dtable->fieldList['PM']['title']    = $lang->execution->PM;
$config->execution->dtable->fieldList['PM']['name']     = 'PM';
$config->execution->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->execution->dtable->fieldList['PM']['sortType'] = true;
$config->execution->dtable->fieldList['PM']['width']    = '100';
$config->execution->dtable->fieldList['PM']['group']    = '2';
$config->execution->dtable->fieldList['PM']['show']     = true;

$config->execution->dtable->fieldList['openedDate']['title']    = $lang->execution->openedDate;
$config->execution->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->execution->dtable->fieldList['openedDate']['type']     = 'date';
$config->execution->dtable->fieldList['openedDate']['sortType'] = true;
$config->execution->dtable->fieldList['openedDate']['width']    = '96';
$config->execution->dtable->fieldList['openedDate']['group']    = '3';

$config->execution->dtable->fieldList['begin']['title']    = $lang->execution->begin;
$config->execution->dtable->fieldList['begin']['name']     = 'begin';
$config->execution->dtable->fieldList['begin']['type']     = 'date';
$config->execution->dtable->fieldList['begin']['sortType'] = true;
$config->execution->dtable->fieldList['begin']['width']    = '96';
$config->execution->dtable->fieldList['begin']['group']    = '3';
$config->execution->dtable->fieldList['begin']['show']     = true;

$config->execution->dtable->fieldList['end']['title']    = $lang->execution->end;
$config->execution->dtable->fieldList['end']['name']     = 'end';
$config->execution->dtable->fieldList['end']['type']     = 'date';
$config->execution->dtable->fieldList['end']['sortType'] = true;
$config->execution->dtable->fieldList['end']['width']    = '96';
$config->execution->dtable->fieldList['end']['group']    = '3';
$config->execution->dtable->fieldList['end']['show']     = true;

$config->execution->dtable->fieldList['realBegan']['title']    = $lang->execution->realBeganAB;
$config->execution->dtable->fieldList['realBegan']['name']     = 'realBegan';
$config->execution->dtable->fieldList['realBegan']['type']     = 'date';
$config->execution->dtable->fieldList['realBegan']['sortType'] = true;
$config->execution->dtable->fieldList['realBegan']['width']    = '106';
$config->execution->dtable->fieldList['realBegan']['group']    = '3';

$config->execution->dtable->fieldList['realEnd']['title']    = $lang->execution->realEndAB;
$config->execution->dtable->fieldList['realEnd']['name']     = 'realEnd';
$config->execution->dtable->fieldList['realEnd']['type']     = 'date';
$config->execution->dtable->fieldList['realEnd']['sortType'] = true;
$config->execution->dtable->fieldList['realEnd']['width']    = '106';
$config->execution->dtable->fieldList['realEnd']['group']    = '3';

$config->execution->dtable->fieldList['totalEstimate']['title']    = $lang->execution->totalEstimate;
$config->execution->dtable->fieldList['totalEstimate']['name']     = 'totalEstimate';
$config->execution->dtable->fieldList['totalEstimate']['type']     = 'number';
$config->execution->dtable->fieldList['totalEstimate']['sortType'] = false;
$config->execution->dtable->fieldList['totalEstimate']['width']    = '64';
$config->execution->dtable->fieldList['totalEstimate']['group']    = '4';
$config->execution->dtable->fieldList['totalEstimate']['show']     = true;

$config->execution->dtable->fieldList['totalConsumed']['title']    = $lang->execution->totalConsumed;
$config->execution->dtable->fieldList['totalConsumed']['name']     = 'totalConsumed';
$config->execution->dtable->fieldList['totalConsumed']['type']     = 'number';
$config->execution->dtable->fieldList['totalConsumed']['sortType'] = false;
$config->execution->dtable->fieldList['totalConsumed']['width']    = '64';
$config->execution->dtable->fieldList['totalConsumed']['group']    = '4';
$config->execution->dtable->fieldList['totalConsumed']['show']     = true;

$config->execution->dtable->fieldList['totalLeft']['title']    = $lang->execution->totalLeft;
$config->execution->dtable->fieldList['totalLeft']['name']     = 'totalLeft';
$config->execution->dtable->fieldList['totalLeft']['type']     = 'number';
$config->execution->dtable->fieldList['totalLeft']['sortType'] = false;
$config->execution->dtable->fieldList['totalLeft']['width']    = '64';
$config->execution->dtable->fieldList['totalLeft']['group']    = '4';
$config->execution->dtable->fieldList['totalLeft']['show']     = true;

$config->execution->dtable->fieldList['progress']['title']    = $lang->execution->progress;
$config->execution->dtable->fieldList['progress']['name']     = 'progress';
$config->execution->dtable->fieldList['progress']['type']     = 'progress';
$config->execution->dtable->fieldList['progress']['sortType'] = false;
$config->execution->dtable->fieldList['progress']['width']    = '64';
$config->execution->dtable->fieldList['progress']['group']    = '4';
$config->execution->dtable->fieldList['progress']['show']     = true;

$config->execution->dtable->fieldList['burn']['title']    = $lang->execution->burn;
$config->execution->dtable->fieldList['burn']['name']     = 'burn';
$config->execution->dtable->fieldList['burn']['type']     = 'burn';
$config->execution->dtable->fieldList['burn']['sortType'] = false;
$config->execution->dtable->fieldList['burn']['width']    = '88';
$config->execution->dtable->fieldList['burn']['group']    = '4';
$config->execution->dtable->fieldList['burn']['show']     = true;

$config->execution->team->dtable->fieldList['account']['title']    = $lang->team->account;
$config->execution->team->dtable->fieldList['account']['name']     = 'realname';
$config->execution->team->dtable->fieldList['account']['type']     = 'user';
$config->execution->team->dtable->fieldList['account']['link']     = array('module' => 'user', 'method' => 'view', 'params' => 'userID={userID}');
$config->execution->team->dtable->fieldList['account']['sortType'] = false;

$config->execution->team->dtable->fieldList['role']['title']    = $lang->team->role;
$config->execution->team->dtable->fieldList['role']['type']     = 'user';
$config->execution->team->dtable->fieldList['role']['sortType'] = false;

$config->execution->team->dtable->fieldList['join']['title'] = $lang->team->join;
$config->execution->team->dtable->fieldList['join']['type']  = 'date';

$config->execution->team->dtable->fieldList['days']['title'] = $lang->team->days;
$config->execution->team->dtable->fieldList['days']['type']  = 'number';

$config->execution->team->dtable->fieldList['hours']['title'] = $lang->team->hours;
$config->execution->team->dtable->fieldList['hours']['type']  = 'number';

$config->execution->team->dtable->fieldList['total']['title'] = $lang->team->totalHours;
$config->execution->team->dtable->fieldList['total']['type']  = 'number';

$config->execution->team->dtable->fieldList['limited']['title']    = $lang->team->limited;
$config->execution->team->dtable->fieldList['limited']['type']     = 'user';
$config->execution->team->dtable->fieldList['limited']['map']      = $lang->team->limitedList;
$config->execution->team->dtable->fieldList['limited']['sortType'] = false;

$config->execution->team->dtable->fieldList['actions']['type']       = 'actions';
$config->execution->team->dtable->fieldList['actions']['actionsMap'] = $config->execution->team->actionList;
