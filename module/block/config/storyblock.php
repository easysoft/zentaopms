<?php
$app->loadLang('story');
if(!isset($config->block->dtable->stories)) $config->block->dtable->stories = new stdclass();
$config->block->dtable->stories->fieldList['id']['name']     = 'id';
$config->block->dtable->stories->fieldList['id']['title']    = $this->lang->story->id;
$config->block->dtable->stories->fieldList['id']['width']    = 80;
$config->block->dtable->stories->fieldList['id']['align']    = 'center';
$config->block->dtable->stories->fieldList['id']['sortType'] = true;

$config->block->dtable->stories->fieldList['title']['name']     = 'title';
$config->block->dtable->stories->fieldList['title']['title']    = $this->lang->story->title;
$config->block->dtable->stories->fieldList['title']['width']    = 80;
$config->block->dtable->stories->fieldList['title']['align']    = 'center';
$config->block->dtable->stories->fieldList['title']['sortType'] = true;

$config->block->dtable->stories->fieldList['pri']['name']     = 'pri';
$config->block->dtable->stories->fieldList['pri']['title']    = $this->lang->story->pri;
$config->block->dtable->stories->fieldList['pri']['width']    = 80;
$config->block->dtable->stories->fieldList['pri']['align']    = 'center';
$config->block->dtable->stories->fieldList['pri']['sortType'] = true;

$config->block->dtable->stories->fieldList['status']['name']     = 'status';
$config->block->dtable->stories->fieldList['status']['title']    = $this->lang->story->statusAB;
$config->block->dtable->stories->fieldList['status']['width']    = 80;
$config->block->dtable->stories->fieldList['status']['align']    = 'center';
$config->block->dtable->stories->fieldList['status']['sortType'] = true;

$config->block->dtable->stories->fieldList['estimate']['name']     = 'estimate';
$config->block->dtable->stories->fieldList['estimate']['title']    = $this->lang->story->estimateAB;
$config->block->dtable->stories->fieldList['estimate']['width']    = 80;
$config->block->dtable->stories->fieldList['estimate']['align']    = 'center';
$config->block->dtable->stories->fieldList['estimate']['sortType'] = true;

$config->block->dtable->stories->fieldList['stage']['name']     = 'stage';
$config->block->dtable->stories->fieldList['stage']['title']    = $this->lang->story->stageAB;
$config->block->dtable->stories->fieldList['stage']['width']    = 80;
$config->block->dtable->stories->fieldList['stage']['align']    = 'center';
$config->block->dtable->stories->fieldList['stage']['sortType'] = true;

$config->block->dtable->stories->fieldList['category']['name']     = 'category';
$config->block->dtable->stories->fieldList['category']['title']    = $this->lang->story->category;
$config->block->dtable->stories->fieldList['category']['width']    = 80;
$config->block->dtable->stories->fieldList['category']['align']    = 'center';
$config->block->dtable->stories->fieldList['category']['sortType'] = true;
