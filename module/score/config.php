<?php
$config->score->rule              = new stdClass();

$config->score->rule->doc         = new stdClass();
$config->score->rule->bug         = new stdClass();
$config->score->rule->todo        = new stdClass();
$config->score->rule->task        = new stdClass();
$config->score->rule->user        = new stdClass();
$config->score->rule->ajax        = new stdClass();
$config->score->rule->story       = new stdClass();
$config->score->rule->block       = new stdClass();
$config->score->rule->build       = new stdClass();
$config->score->rule->search      = new stdClass();
$config->score->rule->release     = new stdClass();
$config->score->rule->project     = new stdClass();
$config->score->rule->tutorial    = new stdClass();
$config->score->rule->testcase    = new stdClass();
$config->score->rule->testtask    = new stdClass();
$config->score->rule->productplan = new stdClass();

/* Score rule. */
$config->score->rule->user->login          = array('times' => 3, 'hour' => 24, 'score' => 1);
$config->score->rule->user->editProfile    = array('times' => 1, 'hour' => 0,  'score' => 10);
$config->score->rule->user->changePassword = array('times' => 1, 'hour' => 0,  'score' => 10);

$config->score->rule->ajax->lastNext          = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->rule->ajax->batchEdit         = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->rule->ajax->quickJump         = array('times' => 1, 'hour' => 0, 'score' => 10);
$config->score->rule->ajax->customMenu        = array('times' => 1, 'hour' => 0, 'score' => 1);
$config->score->rule->ajax->submitPage        = array('times' => 1, 'hour' => 0, 'score' => 1);
$config->score->rule->ajax->selectLang        = array('times' => 1, 'hour' => 0, 'score' => 10);
$config->score->rule->ajax->batchOther        = array('times' => 1, 'hour' => 0, 'score' => 1);
$config->score->rule->ajax->selectTheme       = array('times' => 1, 'hour' => 0, 'score' => 10);
$config->score->rule->ajax->batchCreate       = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->rule->ajax->dragSelected      = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->rule->ajax->showSearchMenu    = array('times' => 1, 'hour' => 0, 'score' => 10);
$config->score->rule->ajax->switchToDataTable = array('times' => 1, 'hour' => 0, 'score' => 1);

$config->score->rule->doc->create = array('times' => 0, 'hour' => 0, 'score' => 5);

$config->score->rule->bug->create         = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->rule->bug->resolve        = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->rule->bug->confirmBug     = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->rule->bug->saveTplModal   = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->rule->bug->createFormCase = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->rule->task->close  = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->rule->task->create = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->rule->task->finish = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->rule->todo->create = array('times' => 5, 'hour' => 24, 'score' => 1);

$config->score->rule->block->set = array('times' => 1, 'hour' => 0, 'score' => 20);

$config->score->rule->story->close  = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->rule->story->create = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->rule->build->create = array('times' => 0, 'hour' => 0, 'score' => 10);

$config->score->rule->project->close  = array('times' => 0, 'hour' => 0, 'score' => 0);
$config->score->rule->project->create = array('times' => 0, 'hour' => 0, 'score' => 10);

$config->score->rule->release->create = array('times' => 0, 'hour' => 0, 'score' => 10);

$config->score->rule->testcase->create = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->rule->tutorial->finish = array('times' => 1, 'hour' => 0, 'score' => 100);

$config->score->rule->testtask->runCase = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->rule->productplan->create = array('times' => 0, 'hour' => 0, 'score' => 10);

$config->score->rule->search->saveQuery         = array('times' => 1, 'hour' => 0, 'score' => 1);
$config->score->rule->search->saveQueryAdvanced = array('times' => 1, 'hour' => 0, 'score' => 1);

$config->score->ruleExtended = array();
$config->score->ruleExtended['story']['close']         = array('createID' => 2);
$config->score->ruleExtended['user']['changePassword'] = array('strength' => array(1 => 2, 2 => 5));
$config->score->ruleExtended['bug']['confirmBug']      = array('severity' => array(1 => 3, 2 => 2, 3 => 1));
$config->score->ruleExtended['bug']['resolve']         = array('severity' => array(1 => 3, 2 => 2, 3 => 1));
$config->score->ruleExtended['task']['finish']         = array('pri'      => array(1 => 2, 2 => 1, 3 => 0));
$config->score->ruleExtended['project']['close']       = array('manager'  => array('close' => 20, 'onTime' => 10),
                                                               'member'   => array('close' => 5,  'onTime' => 5));