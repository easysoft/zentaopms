<?php
$config->doc->createLib->requiredFields = 'name';
$config->doc->editLib->requiredFields   = 'name';
$config->doc->create->requiredFields = 'title';
$config->doc->edit->requiredFields   = 'title';

$config->doc->editor->create = array('id' => 'content', 'tools' => 'fullTools');
$config->doc->editor->edit   = array('id' => 'content', 'tools' => 'fullTools');
