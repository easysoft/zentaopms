<?php
$config->testreport->create = new stdclass();
$config->testreport->create->requiredFields = 'title,owner';

$config->testreport->edit = new stdclass();
$config->testreport->edit->requiredFields = 'title,owner';

$config->testreport->editor = new stdclass();
$config->testreport->editor->create = array('id' => 'report', 'tools' => 'simpleTools');
$config->testreport->editor->edit   = array('id' => 'report', 'tools' => 'simpleTools');
