<?php
$builder = new stdclass();

$builder->company   = array('rows' => 1,    'extends' => array('company'));
$builder->user      = array('rows' => 1000, 'extends' => array('user'));
$builder->dept      = array('rows' => 100,  'extends' => array('dept'));
$builder->action    = array('rows' => 100,  'extends' => array('action'));

$builder->program       = array('rows' => 10, 'extends' => array('project', 'program'));
$builder->project       = array('rows' => 90, 'extends' => array('project', 'project'));
$builder->sprint        = array('rows' => 600, 'extends' => array('project', 'execution'));

$builder->story         = array('rows' => 400, 'extends' => array('story'));
$builder->storymodule   = array('rows' => 800, 'extends' => array('module','storymodule'));
$builder->task          = array('rows' => 600, 'extends' => array('task','task'));
$builder->taskmore      = array('rows' => 300, 'extends' => array('task','moretask'));
$builder->taskspec      = array('rows' => 600, 'extends' => array('taskspec'));
$builder->taskmodule    = array('rows' => 1800, 'extends' => array('module','taskmodule'));
$builder->taskestimate  = array('rows' => 600, 'extends' => array('taskestimate'));
$builder->taskson       = array('rows' => 10,  'extends' => array('task', 'taskson'));
$builder->case          = array('rows' => 400, 'extends' => array('case'));
$builder->bug           = array('rows' => 300, 'extends' => array('bug'));

$builder->product        = array('rows' => 100, 'extends' => array('product'));
$builder->productline    = array('rows' => 20,  'extends' => array('module', 'productline'));
$builder->productplan    = array('rows' => 360, 'extends' => array('productplan'));
$builder->branch         = array('rows' => 240, 'extends' => array('branch'));
$builder->projectproduct = array('rows' => 200, 'extends' => array('projectproduct'));

$builder->team              = array('rows' => 400, 'extends' => array('team'));
$builder->teamtask          = array('rows' => 20, 'extends' => array('team', 'teamtask'));
$builder->stakeholder       = array('rows' => 1, 'extends' => array('stakeholder'));
$builder->stageson          = array('rows' => 30, 'extends' => array('project', 'executionson'));

$builder->build   = array('rows' => 8, 'extends' => array('build'));
$builder->release = array('rows' => 8, 'extends' => array('release'));

$builder->pipeline = array('rows' => 2,  'extends' => array('pipeline'));
$builder->repo     = array('rows' => 1,  'extends' => array('repo'));
$builder->job      = array('rows' => 2,  'extends' => array('job'));
$builder->mr       = array('rows' => 1,  'extends' => array('mr'));
$builder->oauth    = array('rows' => 90, 'extends' => array('oauth'));
