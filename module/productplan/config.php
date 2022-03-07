<?php
$config->productplan = new stdclass();
$config->productplan->create = new stdclass();
$config->productplan->edit   = new stdclass();
$config->productplan->create->requiredFields = 'title';
$config->productplan->edit->requiredFields   = 'title';

$config->productplan->editor = new stdclass();
$config->productplan->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->start  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->close  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->productplan->editor->view   = array('id' => 'lastComment', 'tools' => 'simpleTools');

$config->productplan->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');

$config->productplan->future = '2030-01-01';
