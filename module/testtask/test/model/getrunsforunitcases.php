#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('testrun')->gen(70);
zdTable('case')->gen(100);
zdTable('story')->gen(100);
zdTable('testsuite')->gen(100);
zdTable('suitecase')->gen(100);

/**

title=测试 testtaskModel->getRunsForUnitCases();
timeout=0
cid=1

- 查询测试单ID为1的第一条测试执行结果
 - 第0条的id属性 @4
 - 第0条的project属性 @0
 - 第0条的execution属性 @101
 - 第0条的title属性 @这个是测试用例4
 - 第0条的precondition属性 @这是前置条件4
 - 第0条的keywords属性 @这是关键词4
 - 第0条的type属性 @install
 - 第0条的case属性 @4
 - 第0条的storyTitle属性 @软件需求2
 - 第0条的caseStatus属性 @investigate
 - 第0条的suite属性 @2
 - 第0条的suiteTitle属性 @这是测试套件名称2

- 查询测试单ID为2的第一条测试执行结果
 - 第1条的id属性 @3
 - 第1条的project属性 @0
 - 第1条的execution属性 @101
 - 第1条的title属性 @这个是测试用例3
 - 第1条的precondition属性 @这是前置条件3
 - 第1条的keywords属性 @这是关键词3
 - 第1条的type属性 @config
 - 第1条的case属性 @3
 - 第1条的storyTitle属性 @软件需求2
 - 第1条的caseStatus属性 @blocked
 - 第1条的suite属性 @2
 - 第1条的suiteTitle属性 @这是测试套件名称2

 */

global $tester;
$tester->loadModel('testtask');

r($tester->testtask->getRunsForUnitCases(1)) && p('0:id,project,execution,title,precondition,keywords,type,case,storyTitle,caseStatus,suite,suiteTitle') && e('4,0,101,这个是测试用例4,这是前置条件4,这是关键词4,install,4,软件需求2,investigate,2,这是测试套件名称2');   // 查询测试单ID为1的第一条测试执行结果
r($tester->testtask->getRunsForUnitCases(1)) && p('1:id,project,execution,title,precondition,keywords,type,case,storyTitle,caseStatus,suite,suiteTitle') && e('3,0,101,这个是测试用例3,这是前置条件3,这是关键词3,config,3,软件需求2,blocked,2,这是测试套件名称2');        // 查询测试单ID为2的第一条测试执行结果
