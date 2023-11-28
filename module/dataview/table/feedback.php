<?php
$this->app->loadLang('feedback');

$schema = new stdclass();

$schema->primaryTable = 'feedback';

$schema->tables = array();
$schema->tables['feedback'] = 'zt_feedback';

$schema->fields = array();
$schema->fields['id']           = array('type' => 'number', 'name' => $this->lang->feedback->id);
$schema->fields['type']         = array('type' => 'option', 'name' => $this->lang->feedback->type, 'options' => $this->lang->feedback->typeList);
$schema->fields['status']       = array('type' => 'option', 'name' => $this->lang->feedback->status, 'options' => $this->lang->feedback->statusList);
$schema->fields['closedReason'] = array('type' => 'option', 'name' => $this->lang->feedback->closedReason, 'options' => $this->lang->feedback->closedReasonList);
$schema->fields['solution']     = array('type' => 'option', 'name' => $this->lang->feedback->solution, 'options' => $this->lang->feedback->solutionList);
$schema->fields['public']       = array('type' => 'option', 'name' => $this->lang->feedback->public, 'options' => $this->lang->feedback->publicList);

