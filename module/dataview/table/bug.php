<?php
$this->app->loadLang('bug');
$this->app->loadLang('product');
$this->app->loadLang('story');
$this->app->loadLang('project');
$this->app->loadLang('tree');

$schema = new stdclass();

$schema->primaryTable = 'bug';

$schema->tables = array();
$schema->tables['bug']         = 'zt_bug';
$schema->tables['product']     = 'zt_product';
$schema->tables['story']       = 'zt_story';
$schema->tables['productline'] = 'zt_module';
$schema->tables['program']     = 'zt_project';
$schema->tables['project']     = 'zt_project';
$schema->tables['bugmodule']   = 'zt_module';

$schema->joins = array();
$schema->joins['product']     = 'product.id = bug.product';
$schema->joins['story']       = 'story.id = bug.story';
$schema->joins['productline'] = 'productline.id = product.line';
$schema->joins['program']     = 'program.id = product.program';
$schema->joins['project']     = 'project.id = bug.project';
$schema->joins['bugmodule']   = 'bugmodule.id = bug.module';

$schema->fields = array();
$schema->fields['id']           = array('type' => 'number', 'name' => $this->lang->bug->id);
$schema->fields['title']        = array('type' => 'string', 'name' => $this->lang->bug->title);
$schema->fields['steps']        = array('type' => 'text',   'name' => $this->lang->bug->steps);
$schema->fields['status']       = array('type' => 'option', 'name' => $this->lang->bug->status, 'options' => $this->lang->bug->statusList);
$schema->fields['type']         = array('type' => 'option', 'name' => $this->lang->bug->type, 'options' => $this->lang->bug->typeList);
$schema->fields['confirmed']    = array('type' => 'option', 'name' => $this->lang->bug->confirmed, 'options' => $this->lang->bug->confirmedList);
$schema->fields['severity']     = array('type' => 'option', 'name' => $this->lang->bug->severity, 'options' => $this->lang->bug->severityList);
$schema->fields['product']      = array('type' => 'object', 'name' => $this->lang->bug->product, 'object' => 'product', 'show' => 'product.name');
$schema->fields['project']      = array('type' => 'object', 'name' => $this->lang->bug->project, 'object' => 'project', 'show' => 'project.name');
$schema->fields['bugmodule']    = array('type' => 'object', 'name' => $this->lang->bug->module, 'object' => 'bugmodule', 'show' => 'bugmodule.name');
$schema->fields['story']        = array('type' => 'object', 'name' => $this->lang->story->common, 'object' => 'story', 'show' => 'story.title');
$schema->fields['pri']          = array('type' => 'option', 'name' => $this->lang->bug->pri, 'options' => $this->lang->bug->priList);
$schema->fields['assignedTo']   = array('type' => 'user',   'name' => $this->lang->bug->assignedTo);
$schema->fields['assignedDate'] = array('type' => 'date',   'name' => $this->lang->bug->assignedDate);
$schema->fields['openedBy']     = array('type' => 'user',   'name' => $this->lang->bug->openedBy);
$schema->fields['openedDate']   = array('type' => 'date',   'name' => $this->lang->bug->openedDate);
$schema->fields['resolvedBy']   = array('type' => 'user',   'name' => $this->lang->bug->resolvedBy);
$schema->fields['resolution']   = array('type' => 'option', 'name' => $this->lang->bug->resolution, 'options' => $this->lang->bug->resolutionList);
$schema->fields['resolvedDate'] = array('type' => 'date',   'name' => $this->lang->bug->resolvedDate);
$schema->fields['closedBy']     = array('type' => 'user',   'name' => $this->lang->bug->closedBy);
$schema->fields['closedDate']   = array('type' => 'date',   'name' => $this->lang->bug->closedDate);

$schema->objects = array();

$schema->objects['story'] = array();
$schema->objects['story']['id']    = array('type' => 'number', 'name' => $this->lang->story->id);
$schema->objects['story']['title'] = array('type' => 'string', 'name' => $this->lang->story->common);

$schema->objects['product'] = array();
$schema->objects['product']['id']   = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->objects['product']['name'] = array('type' => 'string', 'name' => $this->lang->product->common);

$schema->objects['project'] = array();
$schema->objects['project']['id']   = array('type' => 'number', 'name' => $this->lang->project->id);
$schema->objects['project']['name'] = array('type' => 'string', 'name' => $this->lang->project->common);

$schema->objects['bugmodule'] = array();
$schema->objects['bugmodule']['id']   = array('type' => 'number', 'name' => $this->lang->bug->id);
$schema->objects['bugmodule']['name'] = array('type' => 'string', 'name' => $this->lang->tree->module);
