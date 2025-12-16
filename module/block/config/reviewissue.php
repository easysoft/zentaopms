<?php
if(in_array($config->edition, array('max', 'ipd')))
{
    global $lang, $app;
    $app->loadLang('reviewissue');

    $config->block->reviewissue = new stdclass();
    $config->block->reviewissue->dtable = new stdclass();
    $config->block->reviewissue->dtable->fieldList = array();
    $config->block->reviewissue->dtable->fieldList['id']       = array('name' => 'id',       'title' => $lang->idAB,                          'type' => 'id' ,    'sort' => 'number');
    $config->block->reviewissue->dtable->fieldList['name']     = array('name' => 'title',    'title' => zget($lang->reviewissue, 'title'),    'type' => 'title',  'sort' => true,  'flex' => 1, 'link' => array('module' => 'reviewissue', 'method' => 'view', 'params' => 'reviewissueID={id}'));
    $config->block->reviewissue->dtable->fieldList['opinion']  = array('name' => 'opinion',  'title' => zget($lang->reviewissue, 'opinion'),  'type' => 'text',   'sort' => true);
    $config->block->reviewissue->dtable->fieldList['type']     = array('name' => 'type',     'title' => zget($lang->reviewissue, 'type'),     'type' => 'category', 'map' => array('review' => $lang->reviewissue->common), 'sort' => true);
    $config->block->reviewissue->dtable->fieldList['status']   = array('name' => 'status',   'title' => zget($lang->reviewissue, 'status'),   'type' => 'status', 'statusMap' => zget($lang->reviewissue, 'statusList', array()), 'sort' => true);

    $config->block->reviewissue->dtable->short = new stdclass();
    $config->block->reviewissue->dtable->short->fieldList['id']     = $config->block->reviewissue->dtable->fieldList['id'];
    $config->block->reviewissue->dtable->short->fieldList['name']   = $config->block->reviewissue->dtable->fieldList['name'];
    $config->block->reviewissue->dtable->short->fieldList['status'] = $config->block->reviewissue->dtable->fieldList['status'];
}
