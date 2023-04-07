<?php
$this->app->loadLang('product');
$this->app->loadLang('productplan');
$this->app->loadLang('story');
$this->app->loadLang('project');
$this->app->loadLang('tree');
$this->app->loadLang('dataview');

$schema = new stdclass();

$schema->primaryTable = 'story';

$schema->tables = array();
$schema->tables['story']        = 'zt_story';
$schema->tables['product']      = 'zt_product';
$schema->tables['productline']  = 'zt_module';
$schema->tables['productplan']  = 'zt_productplan';
$schema->tables['program']      = 'zt_project';
$schema->tables['project']      = 'zt_project';
$schema->tables['storymodule']  = 'zt_module';
$schema->tables['storyspec']    = 'zt_storyspec';

$schema->joins = array();
$schema->joins['product']     = 'product.id = story.product';
$schema->joins['storymodule'] = 'storymodule.id = story.module';
$schema->joins['storyspec']   = 'storyspec.story = story.id';

$schema->fields = array();
$schema->fields['id']           = array('type' => 'number', 'name' => $this->lang->story->id);
$schema->fields['title']        = array('type' => 'string', 'name' => $this->lang->story->title);
$schema->fields['status']       = array('type' => 'option', 'name' => $this->lang->story->status, 'options' => $this->lang->story->statusList);
$schema->fields['stage']        = array('type' => 'option', 'name' => $this->lang->story->stage, 'options' => $this->lang->story->stageList);
$schema->fields['pri']          = array('type' => 'option', 'name' => $this->lang->story->pri, 'options' => $this->lang->story->priList);
$schema->fields['product']      = array('type' => 'object', 'name' => $this->lang->story->product, 'object' => 'product', 'show' => 'product.name');
$schema->fields['storymodule']  = array('type' => 'object', 'name' => $this->lang->story->module, 'object' => 'storymodule', 'show' => 'storymodule.name');
$schema->fields['closedDate']   = array('type' => 'date',   'name' => $this->lang->story->closedDate);
$schema->fields['closedReason'] = array('type' => 'option', 'name' => $this->lang->story->closedReason, 'options' => $this->lang->story->reasonList);
$schema->fields['openedBy']     = array('type' => 'user',   'name' => $this->lang->story->openedBy);
$schema->fields['openedDate']   = array('type' => 'date',   'name' => $this->lang->story->openedDate);

$schema->objects = array();

$schema->objects['product'] = array();
$schema->objects['product']['id']   = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->objects['product']['name'] = array('type' => 'string', 'name' => $this->lang->product->common);

$schema->objects['storymodule'] = array();
$schema->objects['storymodule']['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['storymodule']['name'] = array('type' => 'string', 'name' => $this->lang->tree->name);
