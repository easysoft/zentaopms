<?php
$config->block->projectstatistic = new stdclass();
$config->block->projectstatistic->items = array();
$config->block->projectstatistic->items['cost'][]  = array('field' => 'costs',     'unit' => 'personDay');
$config->block->projectstatistic->items['cost'][]  = array('field' => 'consumed',  'unit' => 'hour');
$config->block->projectstatistic->items['cost'][]  = array('field' => 'remainder', 'unit' => 'hour');

$config->block->projectstatistic->items['story'][] = array('field' => 'storyPoints', 'unit' => 'SP');
$config->block->projectstatistic->items['story'][] = array('field' => 'done',        'unit' => 'unit');
$config->block->projectstatistic->items['story'][] = array('field' => 'undone',      'unit' => 'unit');

$config->block->projectstatistic->items['task'][]  = array('field' => 'tasks',  'unit' => 'unit');
$config->block->projectstatistic->items['task'][]  = array('field' => 'wait',   'unit' => 'unit');
$config->block->projectstatistic->items['task'][]  = array('field' => 'doing',  'unit' => 'unit');

$config->block->projectstatistic->items['bug'][]   = array('field' => 'bugs',      'unit' => 'unit');
$config->block->projectstatistic->items['bug'][]   = array('field' => 'closed',    'unit' => 'unit');
$config->block->projectstatistic->items['bug'][]   = array('field' => 'activated', 'unit' => 'unit');
