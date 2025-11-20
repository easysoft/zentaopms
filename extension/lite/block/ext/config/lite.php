<?php
$config->block->projectstatistic->items['story'] = array();
$config->block->projectstatistic->items['story'][] = array('field' => 'storyPoints', 'unit' => 'unit');
$config->block->projectstatistic->items['story'][] = array('field' => 'done',        'unit' => 'unit');
$config->block->projectstatistic->items['story'][] = array('field' => 'undone',      'unit' => 'unit');

unset($config->block->projectstatistic->items['bug']);
