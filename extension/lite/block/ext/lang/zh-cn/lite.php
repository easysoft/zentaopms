<?php
$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('管理员', '维护公司', '添加用户', '维护权限');
$lang->block->flowchart['project'] = array('项目负责人', '创建项目', '维护团队', "维护目标", '创建看板');
$lang->block->flowchart['dev']     = array('执行人员', '创建任务', '认领任务', '执行任务');

$lang->block->undone   = '未完成';
$lang->block->delaying = '即将到期';
$lang->block->delayed  = '已延期';

$lang->block->titleList['scrumlist'] = '看板列表';
$lang->block->titleList['sprint']    = '看板总览';

$lang->block->myTask = '我的任务';

$lang->block->finishedTasks = '完成的任务数';

$lang->block->story = '目标';

$lang->block->storyCount = '目标数';

/* unset contribute and projectteam. */
unset($lang->block->default['full']['my']['9']);
unset($lang->block->default['full']['my']['6']);

$lang->block->default['full']['my']['5']['title']  = '看板列表';
$lang->block->default['full']['my']['5']['block']  = 'scrumlist';
$lang->block->default['full']['my']['5']['source'] = 'execution';

$lang->block->default['full']['my']['5']['params']['type']    = 'doing';
$lang->block->default['full']['my']['5']['params']['orderBy'] = 'id_desc';
$lang->block->default['full']['my']['5']['params']['count']   = '15';

$lang->block->modules['kanban']['index'] = new stdclass();
$lang->block->modules['kanban']['index']->availableBlocks = new stdclass();
$lang->block->modules['kanban']['index']->availableBlocks->scrumoverview  = "{$lang->projectCommon}概况";
$lang->block->modules['kanban']['index']->availableBlocks->scrumlist      = $lang->executionCommon . '列表';
$lang->block->modules['kanban']['index']->availableBlocks->sprint         = $lang->executionCommon . '总览';
$lang->block->modules['kanban']['index']->availableBlocks->projectdynamic = '最新动态';

$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->project = "{$lang->projectCommon}列表";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks = new stdclass();
$lang->block->modules['execution']->availableBlocks->statistic = $lang->execution->common . '统计';
$lang->block->modules['execution']->availableBlocks->overview  = $lang->execution->common . '总览';
$lang->block->modules['execution']->availableBlocks->list      = $lang->execution->common . '列表';
$lang->block->modules['execution']->availableBlocks->task      = '任务列表';

unset($lang->block->moduleList['product']);
unset($lang->block->moduleList['qa']);
