<?php
$config->block->projectstatistic = new stdclass();
$config->block->projectstatistic->dtable = array();
$config->block->projectstatistic->dtable['story'][] = array('field' => 'storyPoints', 'unit' => 'SP');
$config->block->projectstatistic->dtable['story'][] = array('field' => 'done'       , 'unit' => 'unit');
$config->block->projectstatistic->dtable['story'][] = array('field' => 'undone'     , 'unit' => 'unit');

$config->block->projectstatistic->dtable['cost'][]  = array('field' => 'costs'      , 'unit' => 'personDay');
$config->block->projectstatistic->dtable['cost'][]  = array('field' => 'consumed'   , 'unit' => 'hour');
$config->block->projectstatistic->dtable['cost'][]  = array('field' => 'remainder'  , 'unit' => 'hour');

$config->block->projectstatistic->dtable['task'][]  = array('field' => 'number'     , 'unit' => 'unit');
$config->block->projectstatistic->dtable['task'][]  = array('field' => 'wait'       , 'unit' => 'unit');
$config->block->projectstatistic->dtable['task'][]  = array('field' => 'doing'      , 'unit' => 'unit');

$config->block->projectstatistic->dtable['bug'][]   = array('field' => 'number'     , 'unit' => 'unit');
$config->block->projectstatistic->dtable['bug'][]   = array('field' => 'resolved'   , 'unit' => 'unit');
$config->block->projectstatistic->dtable['bug'][]   = array('field' => 'activated'  , 'unit' => 'unit');
