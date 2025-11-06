#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'1' 属性extra @
timeout=0
cid=1

- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'1' 属性extra @#1 开发任务11
- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'1, 2, 3'
 - 属性extra @#1 开发任务11
- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'999' 属性extra @0
- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'' 属性extra @0
- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'1, 999, 2'
 - 属性extra @#1 开发任务11

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