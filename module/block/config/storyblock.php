<?php
global $lang, $app;
$app->loadLang('story');

$config->block->story = new stdclass();
$config->block->story->dtable = new stdclass();
$config->block->story->dtable->fieldList = array();
$config->block->story->dtable->fieldList['id']       = array('name' => 'id',       'title' => $this->lang->story->id,         'type' => 'id',       'sortType' => true);
$config->block->story->dtable->fieldList['title']    = array('name' => 'title',    'title' => $this->lang->story->title,      'type' => 'title',    'sortType' => true, 'flex' => 1);
$config->block->story->dtable->fieldList['pri']      = array('name' => 'pri',      'title' => $this->lang->story->pri,        'type' => 'pri',      'sortType' => true);
$config->block->story->dtable->fieldList['status']   = array('name' => 'status',   'title' => $this->lang->story->statusAB,   'type' => 'status',   'sortType' => true);
$config->block->story->dtable->fieldList['estimate'] = array('name' => 'estimate', 'title' => $this->lang->story->estimateAB, 'type' => 'category', 'sortType' => true);
$config->block->story->dtable->fieldList['stage']    = array('name' => 'stage',    'title' => $this->lang->story->stageAB,    'type' => 'count',    'sortType' => true);
$config->block->story->dtable->fieldList['category'] = array('name' => 'category', 'title' => $this->lang->story->category,   'type' => 'category', 'sortType' => true);
