<?php
$config->testtask = new stdclass();
$config->testtask->create = new stdclass();
$config->testtask->edit   = new stdclass();
$config->testtask->create->requiredFields = 'product,status,build,begin,end,name';
$config->testtask->edit->requiredFields   = 'build,begin,end,name';

$config->testtask->actions = new stdclass();
$config->testtask->actions->view = array();
$config->testtask->actions->view['mainActions']   = array('start', 'close', 'block', 'activate', 'cases', 'linkCase');
$config->testtask->actions->view['suffixActions'] = array('edit', 'delete');

$config->testtask->importUnit = new stdclass();
$config->testtask->importUnit->requiredFields = 'execution,build,begin,end,name,resultFile';

$config->testtask->editor = new stdclass();
$config->testtask->editor->create           = array('id' => 'desc', 'tools' => 'simpleTools');
$config->testtask->editor->edit             = array('id' => 'desc,report,comment', 'tools' => 'simpleTools');
$config->testtask->editor->view             = array('id' => 'lastComment', 'tools' => 'simpleTools');
$config->testtask->editor->start            = array('id' => 'comment', 'tools' => 'simpleTools');
$config->testtask->editor->close            = array('id' => 'report,comment', 'tools' => 'simpleTools');
$config->testtask->editor->block            = array('id' => 'comment', 'tools' => 'simpleTools');
$config->testtask->editor->activate         = array('id' => 'comment', 'tools' => 'simpleTools');
$config->testtask->editor->importunitresult = array('id' => 'desc', 'tools' => 'simpleTools');
$config->testtask->editor->assignCase       = array('id' => 'comment', 'tools' => 'simpleTools');

$config->testtask->datatable = new stdclass();
$config->testtask->datatable->defaultField = array('id', 'title', 'pri', 'assignedTo', 'openedBy', 'lastRunner', 'lastRunDate', 'lastRunResult', 'actions');

$config->testtask->unitResultRules = new stdclass();
$config->testtask->unitResultRules->common  = array('path' => array('testsuite/testcase', 'testcase'), 'name' => array('classname', 'name'), 'failure' => 'failure', 'skipped' => 'skipped', 'suite' => 'name', 'aliasSuite' => array('classname'));
$config->testtask->unitResultRules->phpunit = array('path' => array('test', 'testsuite/testcase', 'testcase'), 'name' => array('className', 'methodName'), 'aliasName' => array('classname', 'name'), 'failure' => 'failure', 'skipped' => 'skipped', 'suite' => 'name', 'aliasSuite' => array('classname', 'className'));

$config->testtask->report = new stdclass();
$config->testtask->report->options = new stdclass();
$config->testtask->report->options->graph  = new stdclass();
$config->testtask->report->options->type   = 'pie';
$config->testtask->report->options->width  = 500;
$config->testtask->report->options->height = 140;
