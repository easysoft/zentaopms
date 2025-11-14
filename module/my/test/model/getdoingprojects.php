#!/usr/bin/env php
<?php

/**

title=测试 myModel::getDoingProjects();
timeout=0
cid=17282

- 执行doingCount) && isset($result模块的projects方法  @1
- 执行$result->doingCount <= 5 @1
- 执行$hasBasicProperties @1
- 执行$hasProgressProperty @1
- 执行$hasDelayProperty @1

*/

declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// 准备测试数据
zenData('project')->gen('20');  // 生成20个项目数据
zenData('user')->gen('5');     // 生成5个用户数据
zenData('task')->gen('10');    // 生成任务数据用于工时计算

su('admin');

$my = new myTest();

// 测试步骤1：验证返回对象结构
$result = $my->getDoingProjectsTest();
r(is_object($result) && isset($result->doingCount) && isset($result->projects)) && p() && e('1');

// 测试步骤2：验证项目数量限制（最多5个）
r($result->doingCount <= 5) && p() && e('1');

// 测试步骤3：验证项目基本属性
$hasBasicProperties = !empty($result->projects) &&
                     isset($result->projects[0]->name) &&
                     isset($result->projects[0]->status);
r($hasBasicProperties) && p() && e('1');

// 测试步骤4：验证项目进度属性存在
$hasProgressProperty = !empty($result->projects) &&
                      isset($result->projects[0]->progress);
r($hasProgressProperty) && p() && e('1');

// 测试步骤5：验证项目延期属性存在
$hasDelayProperty = !empty($result->projects) &&
                   isset($result->projects[0]->delay);
r($hasDelayProperty) && p() && e('1');