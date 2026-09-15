<?php
$config->user->execution->dtable->name['link'] = array('module' => 'execution', 'method' => 'kanban', 'params' => 'kanbanID={id}');

$config->user->defaultFields['story'] = array('id', 'title', 'pri', 'status', 'openedBy', 'estimate', 'stage');
