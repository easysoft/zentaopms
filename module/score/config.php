<?php
$config->score->user        = new stdClass();
$config->score->tutorial    = new stdClass();
$config->score->ajax        = new stdClass();
$config->score->doc         = new stdClass();
$config->score->todo        = new stdClass();
$config->score->story       = new stdClass();
$config->score->task        = new stdClass();
$config->score->bug         = new stdClass();
$config->score->testcase    = new stdClass();
$config->score->testtask    = new stdClass();
$config->score->build       = new stdClass();
$config->score->project     = new stdClass();
$config->score->productplan = new stdClass();
$config->score->release     = new stdClass();
$config->score->block       = new stdClass();
$config->score->search      = new stdClass();
$config->score->extended    = new stdClass();

/* Score rule. */
$config->score->user->login          = array('times' => 3, 'hour' => 24, 'score' => 1);
$config->score->user->changePassword = array('times' => 1, 'hour' => 0,  'score' => 10);
$config->score->user->editProfile    = array('times' => 1, 'hour' => 0,  'score' => 10);

$config->score->tutorial->finish = array('times' => 1, 'hour' => 0, 'score' => 100);

$config->score->ajax->selectTheme       = array('times' => 1, 'hour' => 0, 'score' => 10);
$config->score->ajax->selectLang        = array('times' => 1, 'hour' => 0, 'score' => 10);
$config->score->ajax->showSearchMenu    = array('times' => 1, 'hour' => 0, 'score' => 10);
$config->score->ajax->dragSelected      = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->ajax->lastNext          = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->ajax->switchToDataTable = array('times' => 1, 'hour' => 0, 'score' => 1);
$config->score->ajax->submitPage        = array('times' => 1, 'hour' => 0, 'score' => 1);
$config->score->ajax->customMenu        = array('times' => 1, 'hour' => 0, 'score' => 1);
$config->score->ajax->quickJump         = array('times' => 1, 'hour' => 0, 'score' => 10);
$config->score->ajax->batchCreate       = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->ajax->batchEdit         = array('times' => 1, 'hour' => 0, 'score' => 20);
$config->score->ajax->batchOther        = array('times' => 1, 'hour' => 0, 'score' => 1);

$config->score->doc->create = array('times' => 0, 'hour' => 0, 'score' => 5);

$config->score->todo->create = array('times' => 5, 'hour' => 24, 'score' => 1);

$config->score->story->create = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->story->close  = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->task->create = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->task->close  = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->task->finish = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->bug->create         = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->bug->confirmBug     = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->bug->createFormCase = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->bug->resolve        = array('times' => 0, 'hour' => 0, 'score' => 1);
$config->score->bug->saveTplModal   = array('times' => 1, 'hour' => 0, 'score' => 20);

$config->score->testcase->create = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->testtask->runCase = array('times' => 0, 'hour' => 0, 'score' => 1);

$config->score->build->create = array('times' => 0, 'hour' => 0, 'score' => 10);

$config->score->project->create = array('times' => 0, 'hour' => 0, 'score' => 10);
$config->score->project->close  = array('times' => 0, 'hour' => 0, 'score' => 0);

$config->score->productplan->create = array('times' => 0, 'hour' => 0, 'score' => 10);

$config->score->release->create = array('times' => 0, 'hour' => 0, 'score' => 10);

$config->score->block->set = array('times' => 1, 'hour' => 0, 'score' => 20);

$config->score->search->saveQuery         = array('times' => 1, 'hour' => 0, 'score' => 1);
$config->score->search->saveQueryAdvanced = array('times' => 1, 'hour' => 0, 'score' => 1);

/* Extended rule. */
$config->score->extended->changePassword = array('strength' => array(1 => 2, 2 => 5));
$config->score->extended->projectClose   = array('manager' => array('close' => 20, 'in' => 10), 'member' => array('close' => 5, 'in' => 5));
$config->score->extended->bugResolve     = array('severity' => array(1 => 3, 2 => 2, 3 => 1));
$config->score->extended->bugConfirmBug  = array('severity' => array(1 => 3, 2 => 2, 3 => 1));
$config->score->extended->taskFinish     = array('pri' => array(1 => 2, 2 => 1, 3 => 0));
$config->score->extended->storyClose     = array('createID' => 2);
