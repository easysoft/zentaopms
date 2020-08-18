<?php
$config->issue->create   = new stdclass();
$config->issue->create->requiredFields      = 'title,type,severity';

$config->issue->editor = new stdclass();
$config->issue->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
