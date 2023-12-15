#!/usr/bin/env php
<?php
/**

title=测试 designModel->assign();
cid=1

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
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('design')->config('design')->gen(1);
zdTable('user')->gen(5);

$designs   = array(0, 1, 2);
$assignTos = array('admin', '');

$designTester = new designTest();
r($designTester->assignTest($designs[0], $assignTos[0])) && p()                  && e('0');                   // 测试设计ID为0，分配给admin
r($designTester->assignTest($designs[1], $assignTos[0])) && p('0:field,old,new') && e('assignedTo,~~,admin'); // 测试设计ID为1，分配给admin
r($designTester->assignTest($designs[1], $assignTos[1])) && p('0:field,old,new') && e('assignedTo,admin,~~'); // 测试设计ID为1，分配给空
r($designTester->assignTest($designs[2], $assignTos[0])) && p()                  && e('0');                   // 测试设计ID不存在时，分配给admin
