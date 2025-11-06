#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao::processCreateChildrenActionExtra();
timeout=0
cid=0

- 测试单个子任务ID >> 期望返回单个任务信息
- 测试多个子任务ID >> 期望返回多个任务信息用逗号分隔
- 测试不存在的任务ID >> 期望返回空字符串
- 测试空字符串 >> 期望返回空字符串
- 测试混合存在和不存在的任务ID >> 期望只返回存在的任务信息

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('task')->loadYaml('task_processcreatechilredactionextra', false, 2)->gen(10);
zenData('user')->gen(5);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateChildrenActionExtraTest('1')) && p('extra') && e('#1 开发任务11');
r($actionTest->processCreateChildrenActionExtraTest('1,2,3')) && p('extra') && e('#1 开发任务11, #2 开发任务12, #3 开发任务13');
r($actionTest->processCreateChildrenActionExtraTest('999')) && p('extra') && e('0');
r($actionTest->processCreateChildrenActionExtraTest('')) && p('extra') && e('0');
r($actionTest->processCreateChildrenActionExtraTest('1,999,2')) && p('extra') && e('#1 开发任务11, #2 开发任务12');
