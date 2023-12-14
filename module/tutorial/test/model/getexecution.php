#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getExecution();
timeout=0
cid=1

- 检查获取 admin 数据的id,project,name,PM,PO,QD,RD
 - 属性id @3
 - 属性project @2
 - 属性name @Test execution
 - 属性PM @admin
 - 属性PO @admin
 - 属性QD @admin
 - 属性RD @admin
- 检查获取 admin 数据的burns
 - 第burns条的0属性 @35
 - 第burns条的1属性 @35
- 检查获取 admin 数据的hours
 - 第hours条的totalEstimate属性 @52
 - 第hours条的totalConsumed属性 @43
 - 第hours条的totalLeft属性 @7
 - 第hours条的progress属性 @86
 - 第hours条的totalReal属性 @50
- 检查获取 user1 数据的id,project,name,PM,PO,QD,RD
 - 属性id @3
 - 属性project @2
 - 属性name @Test execution
 - 属性PM @user1
 - 属性PO @user1
 - 属性QD @user1
 - 属性RD @user1
- 检查获取 user1 数据的burns
 - 第burns条的0属性 @35
 - 第burns条的1属性 @35
- 检查获取 user1 数据的hours
 - 第hours条的totalEstimate属性 @52
 - 第hours条的totalConsumed属性 @43
 - 第hours条的totalLeft属性 @7
 - 第hours条的progress属性 @86
 - 第hours条的totalReal属性 @50

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
$execution = $tutorial->getExecutionTest();
r($execution) && p('id,project,name,PM,PO,QD,RD')                                    && e('3,2,Test execution,admin,admin,admin,admin'); //检查获取 admin 数据的id,project,name,PM,PO,QD,RD
r($execution) && p('burns:0,1')                                                      && e('35,35');                                      //检查获取 admin 数据的burns
r($execution) && p('hours:totalEstimate,totalConsumed,totalLeft,progress,totalReal') && e('52,43,7,86,50');                              //检查获取 admin 数据的hours

su('user1');
$execution = $tutorial->getExecutionTest();
r($execution) && p('id,project,name,PM,PO,QD,RD')                                    && e('3,2,Test execution,user1,user1,user1,user1'); //检查获取 user1 数据的id,project,name,PM,PO,QD,RD
r($execution) && p('burns:0,1')                                                      && e('35,35');                                      //检查获取 user1 数据的burns
r($execution) && p('hours:totalEstimate,totalConsumed,totalLeft,progress,totalReal') && e('52,43,7,86,50');                              //检查获取 user1 数据的hours
