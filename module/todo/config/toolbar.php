<?php
declare(strict_types=1);

global $lang;

$config->todo->toolbar = new stdclass();
$config->todo->toolbar->buttonList = array();
$config->todo->toolbar->buttonList['play']    = array('action' => 'start',    'text' => $lang->todo->beginAB);
$config->todo->toolbar->buttonList['magic']   = array('action' => 'activate', 'text' => $lang->activate);
$config->todo->toolbar->buttonList['off']     = array('action' => 'close',    'text' => $lang->close);
$config->todo->toolbar->buttonList['edit']    = array('action' => 'edit',     'text' => $lang->edit);
$config->todo->toolbar->buttonList['trash']   = array('action' => 'delete',   'text' => $lang->delete);
$config->todo->toolbar->buttonList['checked'] = array('action' => 'finish',   'text' => $lang->todo->reasonList['done']);
