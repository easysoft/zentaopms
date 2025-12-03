#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processCreateChildrenActionExtra();
timeout=0
cid=0

- 测试单个任务ID(ID为1)属性extra @<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=1'  >#1 开发任务11</a>
- 测试三个任务ID(ID为1,2,3)
 - 属性extra @<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=1'  >#1 开发任务11</a>
- 测试单个任务ID(ID为2)属性extra @<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=2'  >#2 开发任务12</a>
- 测试两个任务ID(ID为3,4)
 - 属性extra @<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=3'  >#3 开发任务13</a>
- 测试单个任务ID(ID为5)属性extra @<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=5'  >#5 开发任务15</a>
- 测试两个任务ID(ID为19,20)
 - 属性extra @<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=19'  >#19 开发任务29</a>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('task')->loadYaml('processcreatechidlrenactionextra/task', false, 2)->gen(20);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateChildrenActionExtraTest('1')) && p('extra') && e("<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=1'  >#1 开发任务11</a>"); // 测试单个任务ID(ID为1)
r($actionTest->processCreateChildrenActionExtraTest('1,2,3')) && p('extra') && e("<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=1'  >#1 开发任务11</a>, <a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=2'  >#2 开发任务12</a>, <a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=3'  >#3 开发任务13</a>"); // 测试三个任务ID(ID为1,2,3)
r($actionTest->processCreateChildrenActionExtraTest('2')) && p('extra') && e("<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=2'  >#2 开发任务12</a>"); // 测试单个任务ID(ID为2)
r($actionTest->processCreateChildrenActionExtraTest('3,4')) && p('extra') && e("<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=3'  >#3 开发任务13</a>, <a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=4'  >#4 开发任务14</a>"); // 测试两个任务ID(ID为3,4)
r($actionTest->processCreateChildrenActionExtraTest('5')) && p('extra') && e("<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=5'  >#5 开发任务15</a>"); // 测试单个任务ID(ID为5)
r($actionTest->processCreateChildrenActionExtraTest('19,20')) && p('extra') && e("<a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=19'  >#19 开发任务29</a>, <a href='/processcreatechidlrenactionextra.php?m=task&f=view&taskID=20'  >#20 开发任务30</a>"); // 测试两个任务ID(ID为19,20)