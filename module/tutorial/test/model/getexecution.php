#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getExecution();
timeout=0
cid=1

- 检查获取数据的id属性id @3
- 检查获取数据的PM属性PM @admin
- 检查获取数据的burns第burns条的0属性 @35

*/

$tutorial = new tutorialTest();

r($tutorial->getExecutionTest()) && p('id')      && e('3');     //检查获取数据的id
r($tutorial->getExecutionTest()) && p('PM')      && e('admin'); //检查获取数据的PM
r($tutorial->getExecutionTest()) && p('burns:0') && e('35');    //检查获取数据的burns