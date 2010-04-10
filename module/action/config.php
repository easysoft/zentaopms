<?php
$config->action->objectTables['bug']   = TABLE_BUG;
$config->action->objectTables['story'] = TABLE_STORY;
$config->action->objectTables['case']  = TABLE_CASE;
$config->action->objectTables['task']  = TABLE_TASK;

$config->action->objectNameFields['bug']   = 'title';
$config->action->objectNameFields['story'] = 'title';
$config->action->objectNameFields['case']  = 'title';
$config->action->objectNameFields['task']  = 'name';
