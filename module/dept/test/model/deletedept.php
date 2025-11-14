#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';
su('admin');

zenData('dept')->gen(11);

/**

title=测试 deptModel->deleteDept();
timeout=0
cid=15965

- 删除后统计数量 @10
- 删除后统计数量 @9
- 删除后统计数量 @8
- 删除后统计数量 @7
- 删除后统计数量 @7

*/

$dept = new deptTest();
r($dept->deleteTest('11')) && p() && e('10'); //删除后统计数量
r($dept->deleteTest('10')) && p() && e('9');  //删除后统计数量
r($dept->deleteTest('9'))  && p() && e('8');  //删除后统计数量
r($dept->deleteTest('8'))  && p() && e('7');  //删除后统计数量
r($dept->deleteTest('8'))  && p() && e('7');  //删除后统计数量
