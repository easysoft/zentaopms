<?php
$lang->action->label->execution = "看板|execution|task|executionID=%s";
$lang->action->label->task      = '任务|task|view|taskID=%s';

$lang->action->objectTypes['task'] = '任务';

unset($lang->action->dynamicAction->program);
unset($lang->action->dynamicAction->product);
unset($lang->action->dynamicAction->productplan);
unset($lang->action->dynamicAction->release);
unset($lang->action->dynamicAction->build);
unset($lang->action->dynamicAction->bug);
unset($lang->action->dynamicAction->testtask);
unset($lang->action->dynamicAction->case);
unset($lang->action->dynamicAction->testreport);
unset($lang->action->dynamicAction->testsuite);
unset($lang->action->dynamicAction->caselib);

$lang->action->dynamicAction->task = array();
$lang->action->dynamicAction->task['opened']              = '创建任务';
$lang->action->dynamicAction->task['edited']              = '编辑任务';
$lang->action->dynamicAction->task['commented']           = '备注任务';
$lang->action->dynamicAction->task['assigned']            = '指派任务';
$lang->action->dynamicAction->task['confirmed']           = "确认{$lang->SRCommon}变更";
$lang->action->dynamicAction->task['started']             = '开始任务';
$lang->action->dynamicAction->task['finished']            = '完成任务';
$lang->action->dynamicAction->task['recordestimate']      = '记录工时';
$lang->action->dynamicAction->task['editestimate']        = '编辑工时';
$lang->action->dynamicAction->task['deleteestimate']      = '删除工时';
$lang->action->dynamicAction->task['paused']              = '暂停任务';
$lang->action->dynamicAction->task['closed']              = '关闭任务';
$lang->action->dynamicAction->task['canceled']            = '取消任务';
$lang->action->dynamicAction->task['activated']           = '激活任务';
$lang->action->dynamicAction->task['createchildren']      = '创建子任务';
$lang->action->dynamicAction->task['unlinkparenttask']    = '从父任务取消关联';
$lang->action->dynamicAction->task['deletechildrentask']  = '删除子任务';
$lang->action->dynamicAction->task['linkparenttask']      = '关联到父任务';
$lang->action->dynamicAction->task['linkchildtask']       = '关联子任务';

$lang->action->label->createchildrenstory   = "创建子目标";
$lang->action->label->linkchildstory        = "关联子目标";
$lang->action->label->unlinkchildrenstory   = "取消关联子目标";
$lang->action->label->linkparentstory       = "关联到父目标";
$lang->action->label->unlinkparentstory     = "从父目标取消关联";
$lang->action->label->deletechildrenstory   = "删除子目标";

$lang->action->desc->createchildrenstory = '$date, 由 <strong>$actor</strong> 创建子目标 <strong>$extra</strong>。' . "\n";
$lang->action->desc->linkchildstory      = '$date, 由 <strong>$actor</strong> 关联子目标 <strong>$extra</strong>。' . "\n";
$lang->action->desc->unlinkchildrenstory = '$date, 由 <strong>$actor</strong> 移除子目标 <strong>$extra</strong>。' . "\n";
$lang->action->desc->linkparentstory     = '$date, 由 <strong>$actor</strong> 关联到父目标 <strong>$extra</strong>。' . "\n";
$lang->action->desc->unlinkparentstory   = '$date, 由 <strong>$actor</strong> 从父目标<strong>$extra</strong>取消关联。' . "\n";
$lang->action->desc->deletechildrenstory = '$date, 由 <strong>$actor</strong> 删除子目标<strong>$extra</strong>。' . "\n";
