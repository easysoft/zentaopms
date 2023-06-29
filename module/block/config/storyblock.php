<?php
global $lang, $app;
$app->loadLang('story');

$config->block->story = new stdclass();
$config->block->story->dtable = new stdclass();
$config->block->story->dtable->fieldList = array();
$config->block->story->dtable->fieldList['id']       = array('name' => 'id',       'title' => $this->lang->idAB,              'type' => 'id',       'sortType' => true);
$config->block->story->dtable->fieldList['title']    = array('name' => 'title',    'title' => $this->lang->story->title,      'type' => 'title',    'sortType' => true, 'flex' => 1);
$config->block->story->dtable->fieldList['pri']      = array('name' => 'pri',      'title' => $this->lang->priAB,             'type' => 'pri',      'sortType' => true);
$config->block->story->dtable->fieldList['status']   = array('name' => 'status',   'title' => $this->lang->story->statusAB,   'type' => 'status',   'sortType' => true, 'statusMap' => $lang->story->statusList);
$config->block->story->dtable->fieldList['category'] = array('name' => 'category', 'title' => $this->lang->story->category,   'type' => 'category', 'sortType' => true, 'map' => $lang->story->categoryList);
$config->block->story->dtable->fieldList['estimate'] = array('name' => 'estimate', 'title' => $this->lang->story->estimateAB, 'type' => 'estimate', 'sortType' => true);
$config->block->story->dtable->fieldList['stage']    = array('name' => 'stage',    'title' => $this->lang->story->stageAB,    'type' => 'stage',    'sortType' => true, 'map' => $lang->story->stageList);
