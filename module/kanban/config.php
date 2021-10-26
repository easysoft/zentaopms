<?php
global $lang;
$config->kanban = new stdclass();

$config->kanban->default = new stdclass();
$config->kanban->default->story  = new stdclass();
$config->kanban->default->story->name  = $lang->SRCommon;
$config->kanban->default->story->color = '#7ec5ff';
$config->kanban->default->story->order = '5';

$config->kanban->default->bug = new stdclass();
$config->kanban->default->bug->name  = $lang->bug->common;
$config->kanban->default->bug->color = '#ba55d3';
$config->kanban->default->bug->order = '10';

$config->kanban->default->task = new stdclass();
$config->kanban->default->task->name  = $lang->task->common;
$config->kanban->default->task->color = '#4169e1';
$config->kanban->default->task->order = '15';
