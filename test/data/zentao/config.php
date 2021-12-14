<?php
$builder = new stdclass();

$builder->user = array('rows' => 1000, 'extends' => array('user'));
$builder->dept = array('rows' => 100, 'extends' => array('dept'));

$builder->program = array('rows' => 100, 'extends' => array('project', 'program'));
$builder->project = array('rows' => 100, 'extends' => array('project', 'project'));
$builder->sprint  = array('rows' => 200, 'extends' => array('project', 'execution'));

$builder->product     = array('rows' => 100, 'extends' => array('product'));
$builder->productplan = array('rows' => 360, 'extends' => array('productplan'));
$builder->branch      = array('rows' => 240, 'extends' => array('branch'));

$builder->build   = array('rows' => 8, 'extends' => array('build'));
$builder->release = array('rows' => 8, 'extends' => array('release'));

$builder->pipeline = array('rows' => 1, 'data' => array('pipeline'));
$builder->repo     = array('rows' => 1, 'data' => array('repo'));
$builder->mr       = array('rows' => 1, 'data' => array('mr'));
