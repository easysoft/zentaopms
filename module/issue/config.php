<?php
$config->issue->create   = new stdclass();
$config->issue->edit     = new stdclass();

$config->issue->create->requiredFields      = 'title,type,severity';
$config->issue->edit->requiredFields        = 'title,type,severity';

$config->issue->editor = new stdclass();
$config->issue->editor->create  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->issue->editor->edit    = array('id' => 'desc', 'tools' => 'simpleTools');
$config->issue->editor->cancel  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->issue->editor->resolve = array('id' => 'spec,steps,desc,resolutionComment', 'tools' => 'simpleTools');
