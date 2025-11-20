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

$lang->block->projectstatistic->story = '目标';

$lang->block->default['full']['my'][] = array('title' => '看板列表', 'module' => 'execution', 'code' => 'scrumlist', 'width' => '2', 'height' => '6', 'left' => '0', 'top' => '45', 'params' => array('type' => 'doing', 'orderBy' => 'id_desc', 'count' => '15'));

$lang->block->modules['kanbanproject'] = new stdclass();
$lang->block->modules['kanbanproject']->availableBlocks['scrumoverview']  = "{$lang->projectCommon}概况";
$lang->block->modules['kanbanproject']->availableBlocks['scrumlist']      = $lang->executionCommon . '列表';
$lang->block->modules['kanbanproject']->availableBlocks['sprint']         = $lang->executionCommon . '总览';
$lang->block->modules['kanbanproject']->availableBlocks['projectdynamic'] = '最新动态';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks['project'] = "{$lang->projectCommon}列表";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks['statistic'] = $lang->execution->common . '统计';
$lang->block->modules['execution']->availableBlocks['overview']  = $lang->execution->common . '总览';
$lang->block->modules['execution']->availableBlocks['list']      = $lang->execution->common . '列表';
$lang->block->modules['execution']->availableBlocks['task']      = '任务列表';

unset($lang->block->moduleList['product']);
unset($lang->block->moduleList['qa']);

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['task'] = '任务数';

$lang->block->summary->welcome    = '禅道已陪伴您%s： %s今日期待优秀的您来处理！';
$lang->block->summary->yesterday  = '<strong>昨日</strong>';
$lang->block->summary->noWork     = '您暂未处理任务，';
$lang->block->summary->finishTask = '完成了<a href="' .  helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a>个任务';
$lang->block->summary->fixBug     = '';
