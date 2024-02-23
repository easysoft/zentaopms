<?php
if(in_array($config->edition, array('max', 'ipd')))
{
    global $lang, $app;
    $app->loadLang('risk');

    $config->block->risk = new stdclass();
    $config->block->risk->dtable = new stdclass();
    $config->block->risk->dtable->fieldList = array();
    $config->block->risk->dtable->fieldList['id']       = array('name' => 'id',       'title' => $lang->idAB,           'type' => 'id' ,      'sort' => 'number');
    $config->block->risk->dtable->fieldList['name']     = array('name' => 'name',     'title' => $lang->risk->name,     'type' => 'title',    'sort' => true,  'flex' => 1, 'link' => array('module' => 'risk', 'method' => 'view', 'params' => 'riskID={id}'));
    $config->block->risk->dtable->fieldList['pri']      = array('name' => 'pri',      'title' => $lang->risk->priAB,    'type' => 'status', 'statusMap' => $lang->risk->priList, 'sort' => true);
    $config->block->risk->dtable->fieldList['rate']     = array('name' => 'rate',     'title' => $lang->risk->rate,     'type' => 'number', 'width' => '100px', 'sort' => true);
    $config->block->risk->dtable->fieldList['status']   = array('name' => 'status',   'title' => $lang->risk->status,   'type' => 'status', 'statusMap' => $lang->risk->statusList, 'sort' => true);
    $config->block->risk->dtable->fieldList['strategy'] = array('name' => 'strategy', 'title' => $lang->risk->strategy, 'type' => 'status', 'statusMap' => $lang->risk->strategyList, 'sort' => true);

    $config->block->risk->dtable->short = new stdclass();
    $config->block->risk->dtable->short->fieldList['id']       = $config->block->risk->dtable->fieldList['id'];
    $config->block->risk->dtable->short->fieldList['name']     = $config->block->risk->dtable->fieldList['name'];
    $config->block->risk->dtable->short->fieldList['strategy'] = $config->block->risk->dtable->fieldList['strategy'];
}
