<?php
$config->productplan->create->requiredFields = 'title,begin,end';
$config->productplan->edit->requiredFields   = 'title,begin,end';

$config->productplan->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
