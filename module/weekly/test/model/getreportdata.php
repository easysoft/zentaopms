#!/usr/bin/env php
<?php
/**

title=测试 weeklyModel->getReportData();
timeout=0
cid=19729

- 检查是否存在pv属性 @1
- 检查是否存在ev属性 @1
- 检查是否存在project属性 @1
- 检查是否存在staff属性 @1
- 检查是否存在progress属性 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->loadYaml('project')->gen(5);
zenData('task')->loadYaml('task')->gen(30);
zenData('user')->gen(5);
su('admin');

global $tester;
$weeklyModel = $tester->loadModel('weekly');
$result      = $weeklyModel->getReportData(1);

r(isset($result->pv))       && p() && e('1'); // 检查是否存在pv属性
r(isset($result->ev))       && p() && e('1'); // 检查是否存在ev属性
r(isset($result->project))  && p() && e('1'); // 检查是否存在project属性
r(isset($result->staff))    && p() && e('1'); // 检查是否存在staff属性
r(isset($result->progress)) && p() && e('1'); // 检查是否存在progress属性
