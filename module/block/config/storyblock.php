<?php
global $lang, $app;
$app->loadLang('story');

$config->block->story = new stdclass();
$config->block->story->dtable = new stdclass();
$config->block->story->dtable->fieldList = array();
$config->block->story->dtable->fieldList['id']       = array('name' => 'id',       'title' => $this->lang->idAB,              'type' => 'id',       'sort' => 'number');
$config->block->story->dtable->fieldList['title']    = array('name' => 'title',    'title' => $this->lang->story->title,      'type' => 'title',    'sort' => true, 'flex' => 1, 'link' => array('module' => 'story', 'method' => 'view', 'params' => 'storyID={id}&vision={version}&param=0&storyType={type}'), 'data-toggle' => 'modal', 'data-size' => 'lg');
$config->block->story->dtable->fieldList['pri']      = array('name' => 'pri',      'title' => $this->lang->priAB,             'type' => 'pri',      'sort' => true);
$config->block->story->dtable->fieldList['status']   = array('name' => 'status',   'title' => $this->lang->story->statusAB,   'type' => 'status',   'sort' => true, 'statusMap' => $lang->story->statusList);
$config->block->story->dtable->fieldList['category'] = array('name' => 'category', 'title' => $this->lang->story->category,   'type' => 'category', 'sort' => true, 'map' => $lang->story->categoryList);
$config->block->story->dtable->fieldList['estimate'] = array('name' => 'estimate', 'title' => $this->lang->story->estimateAB, 'type' => 'estimate', 'sort' => 'number');
$config->block->story->dtable->fieldList['stage']    = array('name' => 'stage',    'title' => $this->lang->story->stageAB,    'type' => 'stage',    'sort' => true, 'map' => $lang->story->stageList);

$config->block->story->dtable->short = new stdclass();
$config->block->story->dtable->short->fieldList['id']     = $config->block->story->dtable->fieldList['id'];
$config->block->story->dtable->short->fieldList['title']  = $config->block->story->dtable->fieldList['title'];
$config->block->story->dtable->short->fieldList['status'] = $config->block->story->dtable->fieldList['status'];
