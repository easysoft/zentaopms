#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('task')->loadYaml('task', true)->gen(12);

/**

title=taskModel->getByList();
timeout=0
cid=18791

- 测试空数据 @0
- 测试根据未删除任务的ID获取任务信息
 - 第1条的id属性 @1
 - 第1条的name属性 @开发任务11
- 测试根据未删除任务的ID获取任务信息数量 @9
- 测试根据已删除任务的ID获取任务信息数量 @0
- 测试根据任务的ID获取任务信息数量 @9
- 测试根据不存在任务的ID获取任务信息数量 @0

*/

$taskIdList[] = range(1, 9);
$taskIdList[] = range(10, 12);
$taskIdList[] = range(1, 15);
$taskIdList[] = range(13, 15);

$taskModel = $tester->loadModel('task');
r($taskModel->getByIdList(array()))               && p()            && e('0');            // 测试空数据
r($taskModel->getByIdList($taskIdList[0]))        && p('1:id,name') && e('1,开发任务11'); // 测试根据未删除任务的ID获取任务信息
r(count($taskModel->getByIdList($taskIdList[0]))) && p()            && e('9');            // 测试根据未删除任务的ID获取任务信息数量
r(count($taskModel->getByIdList($taskIdList[1]))) && p()            && e('0');            // 测试根据已删除任务的ID获取任务信息数量
r(count($taskModel->getByIdList($taskIdList[2]))) && p()            && e('9');            // 测试根据任务的ID获取任务信息数量
r(count($taskModel->getByIdList($taskIdList[3]))) && p()            && e('0');            // 测试根据不存在任务的ID获取任务信息数量