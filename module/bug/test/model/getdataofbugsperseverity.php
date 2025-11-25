#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->gen(10);

/**

title=bugModel->getDataOfBugsPerSeverity();
timeout=0
cid=15371

- 获取严重程度1数据
 - 第1条的name属性 @严重程度:1
 - 第1条的value属性 @3

- 获取严重程度2数据
 - 第2条的name属性 @严重程度:2
 - 第2条的value属性 @3

- 获取严重程度3数据
 - 第3条的name属性 @严重程度:3
 - 第3条的value属性 @2

- 获取严重程度4数据
 - 第4条的name属性 @严重程度:4
 - 第4条的value属性 @2

*/

$bug = new bugTest();
r($bug->getDataOfBugsPerSeverityTest()) && p('1:name,value') && e('严重程度:1,3'); // 获取严重程度1数据
r($bug->getDataOfBugsPerSeverityTest()) && p('2:name,value') && e('严重程度:2,3'); // 获取严重程度2数据
r($bug->getDataOfBugsPerSeverityTest()) && p('3:name,value') && e('严重程度:3,2'); // 获取严重程度3数据
r($bug->getDataOfBugsPerSeverityTest()) && p('4:name,value') && e('严重程度:4,2'); // 获取严重程度4数据