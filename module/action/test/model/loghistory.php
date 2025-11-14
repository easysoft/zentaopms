#!/usr/bin/env php
<?php

/**

title=测试 actionModel::logHistory();
timeout=0
cid=14918

- 执行actionTest模块的logHistoryTest方法，参数是$actionIDList[0], $changes1
 - 第0条的field属性 @name
 - 第0条的old属性 @变更前名称
 - 第0条的new属性 @变更后名称
 - 第1条的field属性 @code
 - 第1条的old属性 @变更前编号
 - 第1条的new属性 @变更后编号
- 执行actionTest模块的logHistoryTest方法，参数是$actionIDList[1], $changes2
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @admin
 - 第0条的new属性 @test1
- 执行actionTest模块的logHistoryTest方法，参数是$actionIDList[2], $changes3
 - 第0条的field属性 @name
 - 第0条的old属性 @name1
 - 第0条的new属性 @name2
- 执行actionTest模块的logHistoryTest方法，参数是$actionIDList[3], $changes4
 - 第0条的field属性 @code
 - 第0条的old属性 @code1
 - 第0条的new属性 @code2
- 执行actionTest模块的logHistoryTest方法，参数是$actionIDList[4], $changes5
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @test2
 - 第0条的new属性 @test1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('company')->gen(1);
zenData('user')->gen(5);
zenData('action')->loadYaml('action_loghistory', false, 2)->gen(5);
zenData('history')->gen(0);
su('admin');

$actionIDList = array('1', '2', '3', '4', '5');

$changes1[0] = array('field' => 'name', 'old' => '变更前名称', 'new' => '变更后名称');
$changes1[1] = array('field' => 'code', 'old' => '变更前编号', 'new' => '变更后编号');
$changes2[0] = array('field' => 'assignedTo', 'old' => 'admin', 'new' => 'test1');
$changes3[0] = array('field' => 'name', 'old' => 'name1', 'new' => 'name2');
$changes4[0] = array('field' => 'code', 'old' => 'code1', 'new' => 'code2');
$changes5[0] = array('field' => 'assignedTo', 'old' => 'test2', 'new' => 'test1');

$actionTest = new actionTest();

r($actionTest->logHistoryTest($actionIDList[0], $changes1)) && p('0:field,old,new;1:field,old,new') && e('name,变更前名称,变更后名称;code,变更前编号,变更后编号');
r($actionTest->logHistoryTest($actionIDList[1], $changes2)) && p('0:field,old,new') && e('assignedTo,admin,test1');
r($actionTest->logHistoryTest($actionIDList[2], $changes3)) && p('0:field,old,new') && e('name,name1,name2');
r($actionTest->logHistoryTest($actionIDList[3], $changes4)) && p('0:field,old,new') && e('code,code1,code2');
r($actionTest->logHistoryTest($actionIDList[4], $changes5)) && p('0:field,old,new') && e('assignedTo,test2,test1');