<?php
$config->productplan = new stdclass();
$config->productplan->create = new stdclass();
$config->productplan->edit   = new stdclass();
$config->productplan->start  = new stdclass();
$config->productplan->create->requiredFields = 'title';
$config->productplan->edit->requiredFields   = 'title';
$config->productplan->start->requiredFields  = 'begin,end';

$config->productplan->editor = new stdclass();
$config->productplan->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
