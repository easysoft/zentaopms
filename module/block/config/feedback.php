<?php
if(in_array($config->edition, array('max', 'biz', 'ipd')))
{
    global $lang, $app;
    $app->loadLang('feedback');

    $config->block->feedback = new stdclass();
    $config->block->feedback->dtable = new stdclass();
    $config->block->feedback->dtable->fieldList = array();
    $config->block->feedback->dtable->fieldList['id']       = array('name' => 'id',      'title' => $lang->idAB,                     'type' => 'id' ,      'sort' => 'number');
    $config->block->feedback->dtable->fieldList['title']    = array('name' => 'title',   'title' => zget($lang->feedback, 'title'),   'type' => 'title',    'sort' => true,  'flex' => 1, 'link' => array('module' => 'feedback', 'method' => $config->vision == 'lite' ? 'view' : 'adminView', 'params' => 'feedbackID={id}'));
    $config->block->feedback->dtable->fieldList['product']  = array('name' => 'product', 'title' => zget($lang->feedback, 'product'), 'type' => 'category', 'sort' => true);
    $config->block->feedback->dtable->fieldList['pri']      = array('name' => 'pri',     'title' => zget($lang->feedback, 'pri'),     'type' => 'pri',      'sort' => true);
    $config->block->feedback->dtable->fieldList['status']   = array('name' => 'status',  'title' => zget($lang->feedback, 'status'),  'type' => 'status',   'sort' => true, 'statusMap' => zget($lang->feedback, 'statusList', array()));
    $config->block->feedback->dtable->fieldList['type']     = array('name' => 'type',    'title' => zget($lang->feedback, 'type'),    'type' => 'category', 'sort' => true, 'map' => zget($lang->feedback, 'typeList', array()));

    $config->block->feedback->dtable->short = new stdclass();
    $config->block->feedback->dtable->short->fieldList['id']    = $config->block->feedback->dtable->fieldList['id'];
    $config->block->feedback->dtable->short->fieldList['title'] = $config->block->feedback->dtable->fieldList['title'];
    $config->block->feedback->dtable->short->fieldList['pri']   = $config->block->feedback->dtable->fieldList['pri'];
}
