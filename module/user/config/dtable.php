<?php
$config->user->execution = new stdclass();
$config->user->execution->dtable = new stdclass();
$config->user->execution->dtable->name['link'] = array('module' => 'execution', 'method' => 'view', 'params' => 'executionID={id}');
