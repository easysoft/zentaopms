#!/usr/bin/env php
<?php

/**

title=测试 markModel::getNeededMarks();
timeout=0
cid=17044

- 测试正常查询存在的标记数据 @3
- 测试查询不存在的对象ID @0
- 测试查询task类型的标记数据 @2
- 测试查询所有版本的标记数据 @3
- 测试查询不存在的标记类型 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$mark = zenData('mark');
$mark->objectType->range('story,story,story,task,task');
$mark->objectID->range('1,2,3,1,2');
$mark->version->range('1.0,1.0,1.0,2.0,2.0');
$mark->account->range('admin');
$mark->mark->range('view');
$mark->date->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`');
$mark->extra->range('');
$mark->gen(5);

global $tester;
$markModel = $tester->loadModel('mark');

r(count($markModel->getNeededMarks([1, 2, 3], 'story', '1.0', 'view'))) && p() && e('3'); // 测试正常查询存在的标记数据
r(count($markModel->getNeededMarks([999, 888], 'story', '1.0', 'view'))) && p() && e('0'); // 测试查询不存在的对象ID
r(count($markModel->getNeededMarks([1, 2], 'task', '2.0', 'view'))) && p() && e('2'); // 测试查询task类型的标记数据
r(count($markModel->getNeededMarks([1, 2, 3], 'story', 'all', 'view'))) && p() && e('3'); // 测试查询所有版本的标记数据
r(count($markModel->getNeededMarks([1, 2, 3], 'story', '1.0', 'nonexistent'))) && p() && e('0'); // 测试查询不存在的标记类型