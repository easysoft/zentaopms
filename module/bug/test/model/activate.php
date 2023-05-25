#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

/**

title=bugModel->activate();
timeout=0
cid=1

- 测试激活状态为active的bug1
 - 属性field @activatedCount
 - 属性old @0
 - 属性new @1

- 测试激活状态为active的bug2
 - 属性field @activatedCount
 - 属性old @0
 - 属性new @1

- 测试激活状态为resolved的bug51
 - 属性field @activatedCount
 - 属性old @0
 - 属性new @1

- 测试激活状态为resolved的bug52
 - 属性field @activatedCount
 - 属性old @0
 - 属性new @1

- 测试激活状态为closed的bug81
 - 属性field @activatedCount
 - 属性old @0
 - 属性new @1

- 测试激活状态为closed的bug82
 - 属性field @activatedCount
 - 属性old @0
 - 属性new @1

- 测试激活状态为resolved的bug53，更改版本默认为2个版本
 - 属性field @openedBuild
 - 属性old @trunk
 - 属性new @1,11

*/

$bug = zdTable('bug');
$bug->product->range('1');
$bug->gen(100);

zdTable('project')->config('execution')->gen(100);
zdTable('build')->gen(100);

$bugIDList = array(1, 2, 51, 52, 81, 82, 53);
$buildList = array(1, 11);

$bug = new bugTest();
r($bug->activateObject($bugIDList[0]))                            && p('field,old,new')      && e('activatedCount,0,1');     // 测试激活状态为active的bug1
r($bug->activateObject($bugIDList[1]))                            && p('field,old,new')      && e('activatedCount,0,1');     // 测试激活状态为active的bug2
r($bug->activateObject($bugIDList[2]))                            && p('field,old,new')      && e('activatedCount,0,1');     // 测试激活状态为resolved的bug51
r($bug->activateObject($bugIDList[3]))                            && p('field,old,new')      && e('activatedCount,0,1');     // 测试激活状态为resolved的bug52
r($bug->activateObject($bugIDList[4]))                            && p('field,old,new')      && e('activatedCount,0,1');     // 测试激活状态为closed的bug81
r($bug->activateObject($bugIDList[5]))                            && p('field,old,new')      && e('activatedCount,0,1');     // 测试激活状态为closed的bug82
r($bug->activateObject($bugIDList[6], $buildList, 'openedBuild')) && p('field/old/new', '/') && e('openedBuild/trunk/1,11'); // 测试激活状态为resolved的bug53，更改版本默认为2个版本