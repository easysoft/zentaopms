#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('user')->gen(5);
zenData('project')->loadYaml('project')->gen(5);
zenData('task')->loadYaml('task')->gen(20);
zenData('taskspec')->loadYaml('taskspec')->gen(20);

/**

title=测试taskModel->batchUpdate();
timeout=0
cid=18769

- 检查修改任务名称
 - 第1[0]条的old属性 @开发任务11
 - 第1[0]条的new属性 @修改1
- 检查修改任务模块
 - 第2[0]条的old属性 @0
 - 第2[0]条的new属性 @1
- 检查修改任务类型
 - 第3[0]条的old属性 @test
 - 第3[0]条的new属性 @devel
- 检查修改任务指派人
 - 第4[0]条的old属性 @`^$`
 - 第4[0]条的new属性 @admin
- 检查修改任务状态
 - 第5[0]条的old属性 @wait
 - 第5[0]条的new属性 @done
- 检查修改任务开始日期
 - 第6[0]条的old属性 @2023-01-02
 - 第6[0]条的new属性 @2023-05-25
- 检查修改任务截止日期
 - 第7[0]条的old属性 @2023-01-10
 - 第7[0]条的new属性 @2023-05-25
- 检查修改任务优先级
 - 第8[0]条的old属性 @3
 - 第8[0]条的new属性 @1
- 检查修改任务预计工时
 - 第9[0]条的old属性 @3.00
 - 第9[0]条的new属性 @1
- 检查修改任务消耗工时
 - 第10[0]条的old属性 @0.00
 - 第10[0]条的new属性 @1
- 检查修改任务剩余工时
 - 第11[1]条的old属性 @3.00
 - 第11[1]条的new属性 @1
- 检查修改任务完成者
 - 第12[1]条的old属性 @`^$`
 - 第12[1]条的new属性 @admin
- 检查修改任务关闭者
 - 第13[1]条的old属性 @`^$`
 - 第13[1]条的new属性 @admin
- 检查任务名称必填项 @『任务名称』不能为空。
- 检查任务类型必填项 @『任务类型』不能为空。
- 检查任务所属模块必填项 @『所属模块』不能为空。
- 检查任务优先级必填项 @『优先级』不能为空。
- 检查任务最初预计必填项 @『最初预计』不能为空。
- 检查任务预计开始必填项 @『预计开始』不能为空。
- 检查任务截止日期必填项 @『截止日期』不能为空。

*/

$taskIdList = range(1, 20);

$changeName       = array('id' => 1, 'name' => '修改1');
$changeModule     = array('id' => 2, 'module' => 1);
$changeType       = array('id' => 3, 'type' => 'devel');
$changeAssignedTo = array('id' => 4, 'assignedTo' => 'admin');
$changeStatus     = array('id' => 5, 'status' => 'done');
$changeEstStarted = array('id' => 6, 'estStarted' => '2023-05-25');
$changeDeadline   = array('id' => 7, 'estStarted' => '2023-01-02', 'deadline' => '2023-05-25');
$changePri        = array('id' => 8, 'pri' => 1);
$changeEstimate   = array('id' => 9, 'estimate' => 1);
$changeConsumed   = array('id' => 10, 'status' => 'doing', 'consumed' => 1);
$changeLeft       = array('id' => 11, 'status' => 'doing', 'consumed' => 2, 'left' => 1);
$changeFinishedBy = array('id' => 12, 'status' => 'done', 'finishedBy' => 'admin');
$changeClosedBy   = array('id' => 13, 'status' => 'closed', 'closedBy' => 'admin', 'closedReason' => 'closed');

$emptyName       = array('id' => 14, 'name' => '');
$emptyType       = array('id' => 15, 'type' => '');
$emptyModule     = array('id' => 16, 'module' => 0);
$emptyPri        = array('id' => 17, 'pri' => 0);
$emptyEstimate   = array('id' => 18, 'estimate' => 0);
$emptyEstStarted = array('id' => 19, 'estStarted' => '');
$emptyDeadline   = array('id' => 20, 'deadline' => '');

$taskTester = new taskModelTest();

/* Modify task field. */
r($taskTester->batchUpdateObject($taskIdList, $changeName))       && p('1[0]:old,new')  && e('开发任务11,修改1');      // 检查修改任务名称
r($taskTester->batchUpdateObject($taskIdList, $changeModule))     && p('2[0]:old,new')  && e('0,1');                   // 检查修改任务模块
r($taskTester->batchUpdateObject($taskIdList, $changeType))       && p('3[0]:old,new')  && e('test,devel');            // 检查修改任务类型
r($taskTester->batchUpdateObject($taskIdList, $changeAssignedTo)) && p('4[0]:old,new')  && e('`^$`,admin');            // 检查修改任务指派人
r($taskTester->batchUpdateObject($taskIdList, $changeStatus))     && p('5[0]:old,new')  && e('wait,done');             // 检查修改任务状态
r($taskTester->batchUpdateObject($taskIdList, $changeEstStarted)) && p('6[0]:old,new')  && e('2023-01-02,2023-05-25'); // 检查修改任务开始日期
r($taskTester->batchUpdateObject($taskIdList, $changeDeadline))   && p('7[0]:old,new')  && e('2023-01-10,2023-05-25'); // 检查修改任务截止日期
r($taskTester->batchUpdateObject($taskIdList, $changePri))        && p('8[0]:old,new')  && e('3,1');                   // 检查修改任务优先级
r($taskTester->batchUpdateObject($taskIdList, $changeEstimate))   && p('9[0]:old,new')  && e('3.00,1');                // 检查修改任务预计工时
r($taskTester->batchUpdateObject($taskIdList, $changeConsumed))   && p('10[0]:old,new') && e('0.00,1');                // 检查修改任务消耗工时
r($taskTester->batchUpdateObject($taskIdList, $changeLeft))       && p('11[1]:old,new') && e('3.00,1');                // 检查修改任务剩余工时
r($taskTester->batchUpdateObject($taskIdList, $changeFinishedBy)) && p('12[1]:old,new') && e('`^$`,admin');            // 检查修改任务完成者
r($taskTester->batchUpdateObject($taskIdList, $changeClosedBy))   && p('13[1]:old,new') && e('`^$`,admin');            // 检查修改任务关闭者

/* Check the required fields. */
r($taskTester->batchUpdateObject($taskIdList, $emptyName,       'name'))       && p('0') && e('『任务名称』不能为空。'); // 检查任务名称必填项
r($taskTester->batchUpdateObject($taskIdList, $emptyType,       'type'))       && p('0') && e('『任务类型』不能为空。'); // 检查任务类型必填项
r($taskTester->batchUpdateObject($taskIdList, $emptyModule,     'module'))     && p('0') && e('『所属模块』不能为空。'); // 检查任务所属模块必填项
r($taskTester->batchUpdateObject($taskIdList, $emptyPri,        'pri'))        && p('0') && e('『优先级』不能为空。');   // 检查任务优先级必填项
r($taskTester->batchUpdateObject($taskIdList, $emptyEstimate,   'estimate'))   && p('0') && e('『最初预计』不能为空。'); // 检查任务最初预计必填项
r($taskTester->batchUpdateObject($taskIdList, $emptyEstStarted, 'estStarted')) && p('0') && e('『预计开始』不能为空。'); // 检查任务预计开始必填项
r($taskTester->batchUpdateObject($taskIdList, $emptyDeadline,   'deadline'))   && p('0') && e('『截止日期』不能为空。'); // 检查任务截止日期必填项
