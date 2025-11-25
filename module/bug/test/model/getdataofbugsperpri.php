#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->gen(10);

/**

title=bugModel->getDataOfBugsPerPri();
timeout=0
cid=15369

- 获取优先级为1的数据
 - 第1条的name属性 @优先级:1
 - 第1条的value属性 @3

- 获取优先级为2的数据
 - 第2条的name属性 @优先级:2
 - 第2条的value属性 @3

- 获取优先级为3的数据
 - 第3条的name属性 @优先级:3
 - 第3条的value属性 @2

- 获取优先级为4的数据
 - 第4条的name属性 @优先级:4
 - 第4条的value属性 @2

*/

$bug = new bugTest();
r($bug->getDataOfBugsPerPriTest()) && p('1:name,value') && e('优先级:1,3'); //获取优先级为1的数据
r($bug->getDataOfBugsPerPriTest()) && p('2:name,value') && e('优先级:2,3'); //获取优先级为2的数据
r($bug->getDataOfBugsPerPriTest()) && p('3:name,value') && e('优先级:3,2'); //获取优先级为3的数据
r($bug->getDataOfBugsPerPriTest()) && p('4:name,value') && e('优先级:4,2'); //获取优先级为4的数据