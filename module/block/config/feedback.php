<?php
if(in_array($config->edition, array('max', 'biz', 'ipd')))
{
    global $lang, $app;
    $app->loadLang('feedback');

    $config->block->feedback = new stdclass();
    $config->block->feedback->dtable = new stdclass();
    $config->block->feedback->dtable->fieldList = array();
    $config->block->feedback->dtable->fieldList['id']       = array('name' => 'id',      'title' => $lang->idAB,              'type' => 'id' ,      'sort' => 'number');
    $config->block->feedback->dtable->fieldList['title']    = array('name' => 'title',   'title' => $lang->feedback->title,   'type' => 'title',    'sort' => true,  'flex' => 1, 'link' => array('module' => 'feedback', 'method' => 'view', 'params' => 'feedbackID={id}'));
    $config->block->feedback->dtable->fieldList['product']  = array('name' => 'product', 'title' => $lang->feedback->product, 'type' => 'category', 'sort' => true);
    $config->block->feedback->dtable->fieldList['pri']      = array('name' => 'pri',     'title' => $lang->feedback->pri,     'type' => 'pri',      'sort' => true);
    $config->block->feedback->dtable->fieldList['status']   = array('name' => 'status',  'title' => $lang->feedback->status,  'type' => 'status',   'sort' => true, 'statusMap' => $lang->feedback->statusList);
    $config->block->feedback->dtable->fieldList['type']     = array('name' => 'type',    'title' => $lang->feedback->type,    'type' => 'category', 'sort' => true, 'map' => $lang->feedback->typeList);

    $config->block->feedback->dtable->short = new stdclass();
    $config->block->feedback->dtable->short->fieldList['id']    = $config->block->feedback->dtable->fieldList['id'];
    $config->block->feedback->dtable->short->fieldList['title'] = $config->block->feedback->dtable->fieldList['title'];
    $config->block->feedback->dtable->short->fieldList['pri']   = $config->block->feedback->dtable->fieldList['pri'];
}
