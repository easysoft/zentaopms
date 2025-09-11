#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processCreateChildrenActionExtra();
timeout=0
cid=0

- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'1' 属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=1'  >#1 子任务1</a>
- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'1, 2, 3' 
 - 属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=1'  >#1 子任务1</a>
- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'' 属性extra @
- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'999' 属性extra @
- 执行actionTest模块的processCreateChildrenActionExtraTest方法，参数是'1, 999, 2' 
 - 属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=1'  >#1 子任务1</a>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('子任务1,子任务2,子任务3,子任务4,子任务5,子任务6,子任务7,子任务8,子任务9,子任务10');
$task->project->range('1');
$task->execution->range('1');
$task->assignedTo->range('admin');
$task->status->range('wait');
$task->deleted->range('0');
$task->gen(10);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateChildrenActionExtraTest('1')) && p('extra') && e("<a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=1'  >#1 子任务1</a>");
r($actionTest->processCreateChildrenActionExtraTest('1,2,3')) && p('extra') && e("<a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=1'  >#1 子任务1</a>, <a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=2'  >#2 子任务2</a>, <a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=3'  >#3 子任务3</a>");
r($actionTest->processCreateChildrenActionExtraTest('')) && p('extra') && e('');
r($actionTest->processCreateChildrenActionExtraTest('999')) && p('extra') && e('');
r($actionTest->processCreateChildrenActionExtraTest('1,999,2')) && p('extra') && e("<a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=1'  >#1 子任务1</a>, <a href='/home/z/rzto/module/action/test/tao/processcreatechildrenactionextra.php?m=task&f=view&taskID=2'  >#2 子任务2</a>");