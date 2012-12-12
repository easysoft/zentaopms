<?php
$config->build = new stdclass();
$config->build->create = new stdclass();
$config->build->edit   = new stdclass();
$config->build->create->requiredFields = 'product,name,builder,date';
$config->build->edit->requiredFields   = 'product,name,builder,date';

$config->build->editor = new stdclass();
$config->build->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->build->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
