<?php
$config->mr->actionList = array();
$config->mr->actionList['edit']['icon'] = 'edit';
$config->mr->actionList['edit']['text'] = $lang->mr->edit;
$config->mr->actionList['edit']['hint'] = $lang->mr->edit;
$config->mr->actionList['edit']['url']  = array('module' => 'mr', 'method' => 'edit', 'params' => 'MRID={id}');

$config->mr->actionList['close']['icon'] = 'close';
$config->mr->actionList['close']['text'] = $lang->mr->close;
$config->mr->actionList['close']['hint'] = $lang->mr->close;
$config->mr->actionList['close']['url']  = array('module' => 'mr', 'method' => 'close', 'params' => 'MRID={id}');
