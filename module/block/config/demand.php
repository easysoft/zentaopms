<?php
if($config->edition == 'ipd' && $config->vision == 'or')
{
    global $lang, $app;
    $app->loadLang('demand');

    $config->block->demand = new stdclass();
    $config->block->demand->dtable = new stdclass();
    $config->block->demand->dtable->fieldList = array();
    $config->block->demand->dtable->fieldList['id']       = array('name' => 'id',       'title' => $lang->idAB,                'type' => 'id' ,      'sort' => 'number');
    $config->block->demand->dtable->fieldList['title']    = array('name' => 'title',    'title' => $lang->demand->demandTitle, 'type' => 'title',    'sort' => true,  'flex' => 1, 'link' => array('module' => 'demand', 'method' => 'view', 'params' => 'demandID={id}'));
    $config->block->demand->dtable->fieldList['pri']      = array('name' => 'pri',      'title' => $lang->demand->pri,         'type' => 'pri',      'sort' => true);
    $config->block->demand->dtable->fieldList['status']   = array('name' => 'status',   'title' => $lang->demand->status,      'type' => 'status',   'sort' => true, 'statusMap' => $lang->demand->statusList);
    $config->block->demand->dtable->fieldList['category'] = array('name' => 'category', 'title' => $lang->demand->category,    'type' => 'category', 'sort' => true, 'map' => $lang->demand->categoryList);
    $config->block->demand->dtable->fieldList['duration'] = array('name' => 'duration', 'title' => $lang->demand->duration,    'type' => 'duration', 'sort' => true, 'map' => $lang->demand->durationList);
    $config->block->demand->dtable->fieldList['BSA']      = array('name' => 'BSA',      'title' => $lang->demand->BSA,         'type' => 'BSA',      'sort' => true, 'map' => $lang->demand->bsaList);

    $config->block->demand->dtable->short = new stdclass();
    $config->block->demand->dtable->short->fieldList['id']     = $config->block->demand->dtable->fieldList['id'];
    $config->block->demand->dtable->short->fieldList['title']  = $config->block->demand->dtable->fieldList['title'];
    $config->block->demand->dtable->short->fieldList['pri']    = $config->block->demand->dtable->fieldList['pri'];
    $config->block->demand->dtable->short->fieldList['status'] = $config->block->demand->dtable->fieldList['status'];
}
