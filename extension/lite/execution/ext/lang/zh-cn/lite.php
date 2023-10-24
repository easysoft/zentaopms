<?php
unset($lang->execution->featureBar['all']['undone']);
unset($lang->execution->featureBar['all']['wait']);
unset($lang->execution->featureBar['all']['suspended']);

$lang->execution->createKanban    = '创建看板';
$lang->execution->noExecution     = "暂时没有看板。";
$lang->execution->importTask      = '转入任务';
$lang->execution->batchCreateTask = '批量创建任务';
$lang->execution->linkStory       = "创建{$lang->SRCommon}";

$lang->execution->kanbanGroup['default']    = '默认方式';
$lang->execution->kanbanGroup['story']      = '目标';
$lang->execution->kanbanGroup['module']     = '所属目录';
$lang->execution->kanbanGroup['pri']        = '优先级';
$lang->execution->kanbanGroup['assignedTo'] = '指派人';

$lang->execution->icons['kanban']    = 'kanban';
$lang->execution->icons['task']      = 'list';
$lang->execution->icons['calendar']  = 'calendar';
$lang->execution->icons['gantt']     = 'lane';
$lang->execution->icons['tree']      = 'treemap';
$lang->execution->icons['grouptask'] = 'sitemap';

$lang->execution->aclList['private'] = "私有（团队成员和{$lang->projectCommon}负责人可访问）";

$lang->execution->common = "{$lang->projectCommon}看板";
