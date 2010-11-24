<?php
$config->project->create->requiredFields = 'name,code,team,begin,end';
$config->project->edit->requiredFields   = 'name,code,team,begin,end';

$config->project->editor->create = array('id' => 'desc,goal', 'tools' => 'simpleTools');
$config->project->editor->edit   = array('id' => 'desc,goal', 'tools' => 'simpleTools');
