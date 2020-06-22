<?php
$config->caselib = new stdclass();
$config->caselib->create = new stdclass();
$config->caselib->createcase = new stdclass();
$config->caselib->create->requiredFields     = 'name';
$config->caselib->createcase->requiredFields = 'title,type';

$config->caselib->editor = new stdclass();
$config->caselib->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');

$config->caselib->datatable = new stdclass();
$config->caselib->datatable->defaultField = array('id', 'pri', 'title', 'type', 'assignedTo', 'lastRunner', 'lastRunDate', 'lastRunResult', 'status', 'bugs', 'results', 'actions');

$config->caselib->custom = new stdclass();
$config->caselib->custom->createFields = 'stage,pri,keywords';
$config->caselib->customCreateFields   = 'stage,pri,keywords';
