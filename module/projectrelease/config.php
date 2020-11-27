<?php
$config->projectrelease = new stdclass();
$config->projectrelease->create = new stdclass();
$config->projectrelease->edit   = new stdclass();
$config->projectrelease->create->requiredFields = 'name,date';
$config->projectrelease->edit->requiredFields   = 'name,date,build';

$config->projectrelease->editor = new stdclass();
$config->projectrelease->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->projectrelease->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
