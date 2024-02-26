<?php
if(in_array($config->edition, array('max', 'ipd')))
{
    global $lang, $app;
    $app->loadLang('meeting');

    $config->block->meeting = new stdclass();
    $config->block->meeting->dtable = new stdclass();
    $config->block->meeting->dtable->fieldList = array();
    $config->block->meeting->dtable->fieldList['id']   = array('name' => 'id',   'title' => $lang->idAB,          'type' => 'id' ,      'sort' => 'number');
    $config->block->meeting->dtable->fieldList['name'] = array('name' => 'name', 'title' => $lang->meeting->name, 'type' => 'title',    'sort' => true,  'flex' => 1, 'link' => array('module' => 'meeting', 'method' => 'view', 'params' => 'meetingID={id}'));
    $config->block->meeting->dtable->fieldList['mode'] = array('name' => 'mode', 'title' => $lang->meeting->mode, 'type' => 'category', 'sort' => true, 'map' => $lang->meeting->modeList);
    $config->block->meeting->dtable->fieldList['dept'] = array('name' => 'dept', 'title' => $lang->meeting->dept, 'type' => 'category', 'sort' => true);
    $config->block->meeting->dtable->fieldList['date'] = array('name' => 'date', 'title' => $lang->meeting->date, 'type' => 'date',     'sort' => true);
    $config->block->meeting->dtable->fieldList['host'] = array('name' => 'host', 'title' => $lang->meeting->host, 'type' => 'user',     'sort' => true);

    $config->block->meeting->dtable->short = new stdclass();
    $config->block->meeting->dtable->short->fieldList['id']   = $config->block->meeting->dtable->fieldList['id'];
    $config->block->meeting->dtable->short->fieldList['name'] = $config->block->meeting->dtable->fieldList['name'];
    $config->block->meeting->dtable->short->fieldList['date'] = $config->block->meeting->dtable->fieldList['date'];
}
