<?php
$config->build->create->requiredFields = 'product,name,builder,date';
$config->build->edit->requiredFields   = 'product,name,builder,date';

$config->build->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->build->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
