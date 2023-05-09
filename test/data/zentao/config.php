<?php
$builder = new stdclass();

$builder->company     = array('rows' => 2,    'extends' => array('company'));
$builder->user        = array('rows' => 1000, 'extends' => array('user'));
$builder->dept        = array('rows' => 100,  'extends' => array('dept'));
$builder->pipeline     = array('rows' => 5,   'extends' => array('pipeline'));
$builder->repo         = array('rows' => 1,   'extends' => array('repo'));
$builder->job          = array('rows' => 2,   'extends' => array('job'));
$builder->mr           = array('rows' => 1,   'extends' => array('mr'));
