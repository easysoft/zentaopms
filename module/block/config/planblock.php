<?php
global $lang, $app;
$app->loadLang('productplan');

$config->block->plan = new stdclass();
$config->block->plan->dtable = array();
$config->block->plan->dtable[] = array('name' => 'product', 'title' => $lang->productplan->product, 'flex' => 1, 'type' => 'link', 'sortType' => 1);
$config->block->plan->dtable[] = array('name' => 'title', 'title' => $lang->productplan->title, 'width' => 200, 'sortType' => 1);
$config->block->plan->dtable[] = array('name' => 'begin', 'title' => $lang->productplan->begin, 'width' => 200, 'sortType' => 1);
$config->block->plan->dtable[] = array('name' => 'end', 'title' => $lang->productplan->end, 'width' => 200, 'sortType' => 1);
