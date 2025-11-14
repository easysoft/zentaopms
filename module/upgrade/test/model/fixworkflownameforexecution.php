#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->fixWorkflowNameForExecution();
cid=19519

- 更新执行列表名 @执行列表
- 更新添加执行名 @添加迭代
- 更新删除迭代名 @删除迭代
- 更新设置迭代名 @设置迭代
- 更新执行概况名 @执行概况
- 更新编辑名 @编辑

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

$upgrade = new upgradeTest();

$workflowaction = zenData('workflowaction');
$workflowaction->id->range('1-10');
$workflowaction->module->range('execution');
$workflowaction->action->range('all,create,delete,edit,view,batchedit');
$workflowaction->method->range('browse,create,delete,edit,view,batchoperate');
$workflowaction->type->range('single');
$workflowaction->batchMode->range('different');
$workflowaction->position->range('view');
$workflowaction->show->range('direct');
$workflowaction->order->range('0');
$workflowaction->buildin->range('1');
$workflowaction->createdDate->range('`2025-06-11 10:00:00`');
$workflowaction->editedDate->range('`2025-06-11 10:30:00`');
$workflowaction->gen(6);

r($upgrade->fixWorkflowNameForExecutionTest('all'))       && p('') && e('执行列表');  //更新执行列表名
r($upgrade->fixWorkflowNameForExecutionTest('create'))    && p('') && e('添加迭代');  //更新添加执行名
r($upgrade->fixWorkflowNameForExecutionTest('delete'))    && p('') && e('删除迭代');  //更新删除迭代名
r($upgrade->fixWorkflowNameForExecutionTest('edit'))      && p('') && e('设置迭代');  //更新设置迭代名
r($upgrade->fixWorkflowNameForExecutionTest('view'))      && p('') && e('执行概况');  //更新执行概况名
r($upgrade->fixWorkflowNameForExecutionTest('batchedit')) && p('') && e('编辑');      //更新编辑名
