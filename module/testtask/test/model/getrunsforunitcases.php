#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('testrun')->gen(70);
zenData('case')->gen(100);
zenData('story')->gen(100);
zenData('testsuite')->gen(100);
zenData('suitecase')->gen(100);

/**

title=测试 testtaskModel->getRunsForUnitCases();
timeout=0
cid=19193

- 查询测试单ID为1下ID为4的测试执行结果
 - 第4条的id属性 @4
 - 第4条的project属性 @0
 - 第4条的execution属性 @101
 - 第4条的title属性 @这个是测试用例4
 - 第4条的precondition属性 @这是前置条件4
 - 第4条的keywords属性 @这是关键词4
 - 第4条的type属性 @install
 - 第4条的case属性 @4
 - 第4条的storyTitle属性 @软件需求2
 - 第4条的caseStatus属性 @investigate
 - 第4条的suite属性 @2
 - 第4条的suiteTitle属性 @这是测试套件名称2
- 查询测试单ID为1下ID为3的测试执行结果
 - 第3条的id属性 @3
 - 第3条的project属性 @0
 - 第3条的execution属性 @101
 - 第3条的title属性 @这个是测试用例3
 - 第3条的precondition属性 @这是前置条件3
 - 第3条的keywords属性 @这是关键词3
 - 第3条的type属性 @config
 - 第3条的case属性 @3
 - 第3条的storyTitle属性 @软件需求2
 - 第3条的caseStatus属性 @blocked
 - 第3条的suite属性 @2
 - 第3条的suiteTitle属性 @这是测试套件名称2
- 查询测试单ID为1下ID为2的测试执行结果
 - 第2条的id属性 @2
 - 第2条的project属性 @0
 - 第2条的execution属性 @101
 - 第2条的title属性 @这个是测试用例2
 - 第2条的precondition属性 @这是前置条件2
 - 第2条的keywords属性 @这是关键词2
 - 第2条的type属性 @performance
 - 第2条的case属性 @2
 - 第2条的storyTitle属性 @软件需求2
 - 第2条的caseStatus属性 @normal
 - 第2条的suite属性 @1
 - 第2条的suiteTitle属性 @这是测试套件名称1
- 查询测试单ID为1下ID为1的测试执行结果
 - 第1条的id属性 @1
 - 第1条的project属性 @0
 - 第1条的execution属性 @101
 - 第1条的title属性 @这个是测试用例1
 - 第1条的precondition属性 @这是前置条件1
 - 第1条的keywords属性 @这是关键词1
 - 第1条的type属性 @feature
 - 第1条的case属性 @1
 - 第1条的storyTitle属性 @软件需求2
 - 第1条的caseStatus属性 @wait
 - 第1条的suite属性 @1
 - 第1条的suiteTitle属性 @这是测试套件名称1
- 查询测试单ID为2下ID为5的测试执行结果
 - 第5条的id属性 @5
 - 第5条的project属性 @0
 - 第5条的execution属性 @102
 - 第5条的title属性 @这个是测试用例5
 - 第5条的precondition属性 @这是前置条件5
 - 第5条的keywords属性 @这是关键词5
 - 第5条的type属性 @security
 - 第5条的case属性 @5
 - 第5条的storyTitle属性 @软件需求6
 - 第5条的caseStatus属性 @wait
 - 第5条的suite属性 @3
 - 第5条的suiteTitle属性 @这是测试套件名称3

*/

global $tester;
$tester->loadModel('testtask');

r($tester->testtask->getRunsForUnitCases(1)) && p('4:id,project,execution,title,precondition,keywords,type,case,storyTitle,caseStatus,suite,suiteTitle') && e('4,0,101,这个是测试用例4,这是前置条件4,这是关键词4,install,4,软件需求2,investigate,2,这是测试套件名称2');   // 查询测试单ID为1下ID为4的测试执行结果
r($tester->testtask->getRunsForUnitCases(1)) && p('3:id,project,execution,title,precondition,keywords,type,case,storyTitle,caseStatus,suite,suiteTitle') && e('3,0,101,这个是测试用例3,这是前置条件3,这是关键词3,config,3,软件需求2,blocked,2,这是测试套件名称2');        // 查询测试单ID为1下ID为3的测试执行结果
r($tester->testtask->getRunsForUnitCases(1)) && p('2:id,project,execution,title,precondition,keywords,type,case,storyTitle,caseStatus,suite,suiteTitle') && e('2,0,101,这个是测试用例2,这是前置条件2,这是关键词2,performance,2,软件需求2,normal,1,这是测试套件名称1');    // 查询测试单ID为1下ID为2的测试执行结果
r($tester->testtask->getRunsForUnitCases(1)) && p('1:id,project,execution,title,precondition,keywords,type,case,storyTitle,caseStatus,suite,suiteTitle') && e('1,0,101,这个是测试用例1,这是前置条件1,这是关键词1,feature,1,软件需求2,wait,1,这是测试套件名称1');          // 查询测试单ID为1下ID为1的测试执行结果
r($tester->testtask->getRunsForUnitCases(2)) && p('5:id,project,execution,title,precondition,keywords,type,case,storyTitle,caseStatus,suite,suiteTitle') && e('5,0,102,这个是测试用例5,这是前置条件5,这是关键词5,security,5,软件需求6,wait,3,这是测试套件名称3');         // 查询测试单ID为2下ID为5的测试执行结果
