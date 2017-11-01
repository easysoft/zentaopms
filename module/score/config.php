<?php
$config->score = new stdClass();
//user rule
$config->score->user                 = new stdClass();
$config->score->user->login          = array('num' => 3, 'time' => 24, 'score' => 1, 'ext' => '');
$config->score->user->changePassword = array('num' => 1, 'time' => 0, 'score' => 10, 'ext' => array(1 => 2, 2 => 5));
$config->score->user->editProfile    = array('num' => 1, 'time' => 0, 'score' => 10, 'ext' => '');
//tutorial rule
$config->score->tutorial          = new stdClass();
$config->score->tutorial->keepAll = array('num' => 1, 'time' => 0, 'score' => 100, 'ext' => '');
//javascript ajax rule
$config->score->ajax                    = new stdClass();
$config->score->ajax->selectTheme       = array('num' => 1, 'time' => 0, 'score' => 10, 'ext' => '');
$config->score->ajax->selectLang        = array('num' => 1, 'time' => 0, 'score' => 10, 'ext' => '');
$config->score->ajax->showSearchMenu    = array('num' => 1, 'time' => 0, 'score' => 10, 'ext' => '');
$config->score->ajax->dragSelected      = array('num' => 1, 'time' => 0, 'score' => 20, 'ext' => '');
$config->score->ajax->lastNext          = array('num' => 1, 'time' => 0, 'score' => 20, 'ext' => '');
$config->score->ajax->switchToDataTable = array('num' => 1, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->ajax->submitPage        = array('num' => 1, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->ajax->customMenu        = array('num' => 1, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->ajax->quickJump         = array('num' => 1, 'time' => 0, 'score' => 10, 'ext' => '');
$config->score->ajax->batchCreate       = array('num' => 1, 'time' => 0, 'score' => 20, 'ext' => '');
$config->score->ajax->batchEdit         = array('num' => 1, 'time' => 0, 'score' => 20, 'ext' => '');
//doc rule
$config->score->doc         = new stdClass();
$config->score->doc->create = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => '');
//todo rule
$config->score->todo         = new stdClass();
$config->score->todo->create = array('num' => 5, 'time' => 24, 'score' => 1, 'ext' => '');
//story rule
$config->score->story         = new stdClass();
$config->score->story->create = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->story->close  = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => array('createID' => 2));
//task rule
$config->score->task         = new stdClass();
$config->score->task->create = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->task->close  = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->task->finish = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => array(1 => 2, 2 => 1, 3 => 0));
//bug rule
$config->score->bug                 = new stdClass();
$config->score->bug->create         = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->bug->confirmBug     = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => array(1 => 3, 2 => 2, 3 => 1));
$config->score->bug->createFormCase = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->bug->resolve        = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => array(1 => 3, 2 => 2, 3 => 1));
$config->score->bug->saveTplModal   = array('num' => 0, 'time' => 0, 'score' => 20, 'ext' => '');
//testcase rule
$config->score->testcase         = new stdClass();
$config->score->testcase->create = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => '');
//testtask rule
$config->score->testtask          = new stdClass();
$config->score->testtask->runCase = array('num' => 0, 'time' => 0, 'score' => 1, 'ext' => '');
//bulid rule
$config->score->build         = new stdClass();
$config->score->build->create = array('num' => 0, 'time' => 0, 'score' => 10, 'ext' => '');
//project rule
$config->score->project         = new stdClass();
$config->score->project->create = array('num' => 0, 'time' => 0, 'score' => 10, 'ext' => '');
$config->score->project->close  = array('num' => 0, 'time' => 0, 'score' => 0, 'ext' => array('manager' => array(20, 10), 'member' => array(5, 5)));
//productplan rule
$config->score->productplan         = new stdClass();
$config->score->productplan->create = array('num' => 0, 'time' => 0, 'score' => 10, 'ext' => '');
//release rule
$config->score->release         = new stdClass();
$config->score->release->create = array('num' => 0, 'time' => 0, 'score' => 10, 'ext' => '');
//block rule
$config->score->block      = new stdClass();
$config->score->block->set = array('num' => 1, 'time' => 0, 'score' => 20, 'ext' => '');
//search rule
$config->score->search                    = new stdClass();
$config->score->search->saveQuery         = array('num' => 1, 'time' => 0, 'score' => 1, 'ext' => '');
$config->score->search->saveQueryAdvanced = array('num' => 1, 'time' => 0, 'score' => 1, 'ext' => '');
