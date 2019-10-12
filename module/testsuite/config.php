<?php
$config->testsuite = new stdclass();
$config->testsuite->create     = new stdclass();
$config->testsuite->edit       = new stdclass();
$config->testsuite->create->requiredFields = 'name';
$config->testsuite->edit->requiredFields   = 'name';

$config->testsuite->editor = new stdclass();
$config->testsuite->editor->create    = array('id' => 'desc', 'tools' => 'simpleTools');
$config->testsuite->editor->edit      = array('id' => 'desc', 'tools' => 'simpleTools');

$config->testsuite->datatable = new stdclass();
$config->testsuite->datatable->defaultField = array('id', 'pri', 'title', 'type', 'assignedTo', 'lastRunner', 'lastRunDate', 'lastRunResult', 'status', 'bugs', 'results', 'actions');

$config->testsuite->custom = new stdclass();
$config->testsuite->custom->createFields = 'stage,pri,keywords';
$config->testsuite->customCreateFields   = 'stage,pri,keywords';
