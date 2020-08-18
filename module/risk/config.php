<?php
$config->risk->editor = new stdclass();
$config->risk->editor->create   = array('id' => 'prevention,remedy', 'tools' => 'simpleTools');
$config->risk->editor->edit     = array('id' => 'prevention,remedy,resolution', 'tools' => 'simpleTools');
$config->risk->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');
$config->risk->editor->cancel   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->risk->editor->close    = array('id' => 'resolution', 'tools' => 'simpleTools');
$config->risk->editor->track    = array('id' => 'prevention,resolution,comment', 'tools' => 'simpleTools');

$config->risk->create = new stdclass();
$config->risk->create->requiredFields = 'name';
