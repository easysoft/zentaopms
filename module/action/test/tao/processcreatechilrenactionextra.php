#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao::processCreateChildrenActionExtra();
timeout=0
cid=0

- 测试单个有效任务ID >> 返回格式化的任务信息
- 测试多个有效任务ID >> 返回多个任务信息
- 测试不存在的任务ID >> 返回空字符串
- 测试空字符串输入 >> 返回空字符串
- 测试混合有效和无效ID >> 只返回有效任务信息
- 测试大量任务ID >> 正确处理所有有效任务
- 测试包含空格的ID字符串 >> 正确处理并过滤空格

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('task')->loadYaml('task_processcreatechilredactionextra', false, 2)->gen(10);
zenData('user')->gen(5);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateChildrenActionExtraTest('1')) && p('extra') && e('#1 开发任务11');
r($actionTest->processCreateChildrenActionExtraTest('1,2,3')) && p('extra') && e('#1 开发任务11, #2 开发任务12, #3 开发任务13');
r($actionTest->processCreateChildrenActionExtraTest('999')) && p('extra') && e('~~');
r($actionTest->processCreateChildrenActionExtraTest('')) && p('extra') && e('~~');
r($actionTest->processCreateChildrenActionExtraTest('1,999,2')) && p('extra') && e('#1 开发任务11, #2 开发任务12');
r($actionTest->processCreateChildrenActionExtraTest('1,2,3,4,5')) && p('extra') && e('#1 开发任务11, #2 开发任务12, #3 开发任务13, #4 开发任务14, #5 开发任务15');
r($actionTest->processCreateChildrenActionExtraTest('1, 2, 3')) && p('extra') && e('#1 开发任务11, #2 开发任务12, #3 开发任务13');
