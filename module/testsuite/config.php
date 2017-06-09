<?php
$config->testsuite = new stdclass();
$config->testsuite->create     = new stdclass();
$config->testsuite->edit       = new stdclass();
$config->testsuite->createlib  = new stdclass();
$config->testsuite->createcase = new stdclass();
$config->testsuite->create->requiredFields     = 'name';
$config->testsuite->edit->requiredFields       = 'name';
$config->testsuite->createlib->requiredFields  = 'name';
$config->testsuite->createcase->requiredFields = 'title,type';

$config->testsuite->editor = new stdclass();
$config->testsuite->editor->create    = array('id' => 'desc', 'tools' => 'simpleTools');
$config->testsuite->editor->edit      = array('id' => 'desc', 'tools' => 'simpleTools');
$config->testsuite->editor->createlib = array('id' => 'desc', 'tools' => 'simpleTools');

$config->testsuite->datatable = new stdclass();
$config->testsuite->datatable->defaultField = array('id', 'pri', 'title', 'type', 'assignedTo', 'lastRunner', 'lastRunDate', 'lastRunResult', 'status', 'bugs', 'results', 'actions');

$config->filterParam->cookie['testsuite']['common']['hold']  = 'lastCaseLib,lastProduct,preProductID';
$config->filterParam->cookie['testsuite']['library']['hold'] = 'preCaseLibID,libCaseModule';
$config->filterParam->cookie['testsuite']['common']['params']['lastCaseLib']['int']    = '';
$config->filterParam->cookie['testsuite']['common']['params']['lastProduct']['int']    = '';
$config->filterParam->cookie['testsuite']['common']['params']['preProductID']['int']   = '';
$config->filterParam->cookie['testsuite']['library']['params']['preCaseLibID']['int']  = '';
$config->filterParam->cookie['testsuite']['library']['params']['libCaseModule']['int'] = '';
