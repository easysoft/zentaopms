#!/usr/bin/env php
<?php

/**

title=测试 searchModel->getSummary();
timeout=0
cid=1

- 测试获取所有有权限的对象类型
 -  @bug
 - 属性1 @build
 - 属性2 @case
 - 属性3 @doc
 - 属性4 @product
 - 属性5 @productplan
 - 属性6 @project
 - 属性7 @release
 - 属性8 @story
 - 属性9 @requirement
 - 属性10 @task
 - 属性11 @testtask
 - 属性12 @todo
 - 属性13 @effort
 - 属性14 @testsuite
 - 属性15 @caselib
 - 属性16 @testreport
 - 属性17 @program
 - 属性18 @execution
- 测试获取所有有权限的对象类型
 -  @bug
 - 属性1 @build
 - 属性2 @case
 - 属性3 @doc
 - 属性4 @product
 - 属性5 @productplan
 - 属性6 @project
 - 属性7 @release
 - 属性8 @story
 - 属性9 @requirement
 - 属性10 @task
 - 属性11 @testtask
 - 属性12 @todo
 - 属性13 @effort
 - 属性14 @testsuite
 - 属性15 @caselib
 - 属性16 @testreport
 - 属性17 @execution
- 测试获取有权限的指定的对象类型
 -  @project
 - 属性1 @story
 - 属性2 @bug

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

$typeList   = array('all', array('project', 'story', 'bug'));
$systemMode = array('ALM', 'light');

$search = new searchTest();
r($search->getAllowedObjectsTest($typeList[0], $systemMode[0])) && p('0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18') && e('bug,build,case,doc,product,productplan,project,release,story,requirement,task,testtask,todo,effort,testsuite,caselib,testreport,program,execution'); //测试获取所有有权限的对象类型
r($search->getAllowedObjectsTest($typeList[0], $systemMode[1])) && p('0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17') && e('bug,build,case,doc,product,productplan,project,release,story,requirement,task,testtask,todo,effort,testsuite,caselib,testreport,execution'); //测试获取所有有权限的对象类型
r($search->getAllowedObjectsTest($typeList[1], $systemMode[0])) && p('0,1,2') && e('project,story,bug'); //测试获取有权限的指定的对象类型