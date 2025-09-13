#!/usr/bin/env php
<?php

/**

title=测试 bugZen::initBug();
timeout=0
cid=0

- 执行bugTest模块的initBugTest方法，参数是array 
 - 属性projectID @0
 - 属性moduleID @0
 - 属性executionID @0
 - 属性productID @0
 - 属性taskID @0
 - 属性storyID @0
 - 属性buildID @0
 - 属性caseID @0
 - 属性runID @0
 - 属性testtask @0
 - 属性version @0
 - 属性title @~~
 - 属性severity @3
 - 属性type @codeerror
 - 属性pri @3
- 执行bugTest模块的initBugTest方法，参数是array 
 - 属性title @测试bug标题
 - 属性severity @3
 - 属性type @codeerror
 - 属性pri @3
- 执行bugTest模块的initBugTest方法，参数是array 
 - 属性title @多字段测试
 - 属性pri @1
 - 属性severity @2
 - 属性type @designdefect
- 执行bugTest模块的initBugTest方法，参数是array 
 - 属性customField @test
 - 属性invalidField @invalid
 - 属性severity @3
 - 属性type @codeerror
- 执行bugTest模块的initBugTest方法，参数是array 
 - 属性projectID @1
 - 属性moduleID @2
 - 属性executionID @3
 - 属性productID @4
 - 属性title @完整bug
 - 属性assignedTo @admin
 - 属性pri @2
 - 属性severity @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

su('admin');

$bugTest = new bugTest();

r($bugTest->initBugTest(array())) && p('projectID,moduleID,executionID,productID,taskID,storyID,buildID,caseID,runID,testtask,version,title,severity,type,pri') && e('0,0,0,0,0,0,0,0,0,0,0,~~,3,codeerror,3');

r($bugTest->initBugTest(array('title' => '测试bug标题'))) && p('title,severity,type,pri') && e('测试bug标题,3,codeerror,3');

r($bugTest->initBugTest(array('title' => '多字段测试', 'pri' => 1, 'severity' => 2, 'type' => 'designdefect'))) && p('title,pri,severity,type') && e('多字段测试,1,2,designdefect');

r($bugTest->initBugTest(array('customField' => 'test', 'invalidField' => 'invalid'))) && p('customField,invalidField,severity,type') && e('test,invalid,3,codeerror');

r($bugTest->initBugTest(array('projectID' => 1, 'moduleID' => 2, 'executionID' => 3, 'productID' => 4, 'title' => '完整bug', 'assignedTo' => 'admin', 'pri' => 2, 'severity' => 1))) && p('projectID,moduleID,executionID,productID,title,assignedTo,pri,severity') && e('1,2,3,4,完整bug,admin,2,1');