#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('task')->loadYaml('task')->gen(9);

/**

title=taskModel->getRemindBugLink();
timeout=0
cid=18882

- 获取是否请求成功属性result @success
- 获取是否刷新页面属性load @1
- 获取是否关闭弹窗属性closeModal @1
- 获取返回的链接属性link @~~
- 获取回调函数属性callback @zui.Modal.confirm

*/

global $tester;
$taskModel = $tester->loadModel('task');

$oldTask = $taskModel->getByID(1);
$newTask = clone $oldTask;
$newTask->status = 'done';

$changes = common::createChanges($oldTask, $newTask);
$result = $taskModel->getRemindBugLink($newTask, $changes);

r($result) && p('result')     && e('success'); // 获取是否请求成功
r($result) && p('load')       && e('1');       // 获取是否刷新页面
r($result) && p('closeModal') && e('1');       // 获取是否关闭弹窗
r($result) && p('link')       && e('~~');      // 获取返回的链接
r(mb_substr($result['callback'], '0', '17')) && p('callback') && e(`zui.Modal.confirm`); // 获取回调函数
