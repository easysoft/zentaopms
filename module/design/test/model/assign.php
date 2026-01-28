#!/usr/bin/env php
<?php
/**

title=测试 designModel->assign();
cid=15982

- 测试设计ID为0，分配给admin @0
- 测试设计ID为1，分配给admin
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @~~
 - 第0条的new属性 @admin
- 测试设计ID为1，分配给空
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @admin
 - 第0条的new属性 @~~
- 测试设计ID不存在时，分配给admin @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('design')->loadYaml('design')->gen(1);
zenData('user')->gen(5);

$designs   = array(0, 1, 2);
$assignTos = array('admin', '');

$designTester = new designModelTest();
r($designTester->assignTest($designs[0], $assignTos[0])) && p()                  && e('0');                   // 测试设计ID为0，分配给admin
r($designTester->assignTest($designs[1], $assignTos[0])) && p('0:field,old,new') && e('assignedTo,~~,admin'); // 测试设计ID为1，分配给admin
r($designTester->assignTest($designs[1], $assignTos[1])) && p('0:field,old,new') && e('assignedTo,admin,~~'); // 测试设计ID为1，分配给空
r($designTester->assignTest($designs[2], $assignTos[0])) && p()                  && e('0');                   // 测试设计ID不存在时，分配给admin
