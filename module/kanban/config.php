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

$config->kanban->column = new stdclass();
$config->kanban->column->status['story']['backlog']    = 'active';
$config->kanban->column->status['story']['developing'] = 'active';
$config->kanban->column->status['story']['developed']  = 'active';
$config->kanban->column->status['story']['testing']    = 'active';
$config->kanban->column->status['story']['tested']     = 'active';
$config->kanban->column->status['story']['verified']   = 'active';
$config->kanban->column->status['story']['released']   = 'active';
$config->kanban->column->status['story']['closed']     = 'closed';

$config->kanban->column->status['bug']['unconfirmed'] = 'active';
$config->kanban->column->status['bug']['confirmed']   = 'active';
$config->kanban->column->status['bug']['fixed']       = 'resolved';
$config->kanban->column->status['bug']['closed']      = 'closed';

$config->kanban->column->status['task']['developing'] = 'doing';
$config->kanban->column->status['task']['developed']  = 'done';
$config->kanban->column->status['task']['wait']       = 'wait';
$config->kanban->column->status['task']['pause']      = 'pause';
$config->kanban->column->status['task']['canceled']   = 'canceled';
$config->kanban->column->status['task']['closed']     = 'closed';

$config->kanban->column->stage['story']['backlog']    = 'active';
$config->kanban->column->stage['story']['developing'] = 'developing';
$config->kanban->column->stage['story']['developed']  = 'developed';
$config->kanban->column->stage['story']['testing']    = 'testing';
$config->kanban->column->stage['story']['tested']     = 'tested';
$config->kanban->column->stage['story']['verified']   = 'verified';
$config->kanban->column->stage['story']['released']   = 'released';
$config->kanban->column->stage['story']['closed']     = 'closed';
