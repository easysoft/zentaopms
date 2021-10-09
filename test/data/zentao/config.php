<?php
$builder = new stdclass();
$builder->programGrade1 = array('rows' => 40, 'data' => array('project', 'program', 'programgrade1'));
$builder->programGrade2 = array('rows' => 40, 'data' => array('project', 'program', 'programgrade2'));
$builder->programGrade3 = array('rows' => 40, 'data' => array('project', 'program', 'programgrade3'));
$builder->projectGrade1 = array('rows' => 10, 'data' => array('project', 'commonproject', 'projectgrade1'));
$builder->projectGrade2 = array('rows' => 30, 'data' => array('project', 'commonproject', 'projectgrade2'));
$builder->projectGrade3 = array('rows' => 40, 'data' => array('project', 'commonproject', 'projectgrade3'));
$builder->projectGrade4 = array('rows' => 40, 'data' => array('project', 'commonproject', 'projectgrade4'));
$builder->execution     = array('rows' => 700, 'data' => array('project', 'execution'));

$builder->deptGrade1 = array('rows' => 15, 'data' => array('dept', 'deptgrade1'));
$builder->deptGrade2 = array('rows' => 10, 'data' => array('dept', 'deptgrade2'));
$builder->deptGrade3 = array('rows' => 10, 'data' => array('dept', 'deptgrade3'));

$builder->company     = array('rows' => 1,   'data' => array('company'));
$builder->user        = array('rows' => 346, 'data' => array('user'));
$builder->usergroup   = array('rows' => 349, 'data' => array('usergroup'));
$builder->product     = array('rows' => 200, 'data' => array('product'));
$builder->productplan = array('rows' => 360, 'data' => array('productplan'));
$builder->branch      = array('rows' => 240, 'data' => array('branch'));

$builder->build   = array('rows' => 8, 'data' => array('build'));
$builder->release = array('rows' => 8, 'data' => array('release'));

$builder->blockDefault   = array('rows' => 150, 'data' => array('block', 'blockdefault'));
$builder->blockRandom    = array('rows' => 120, 'data' => array('block', 'blockrandom'));
$builder->projectProduct = array('rows' => 21,  'data' => array('projectproduct'));
