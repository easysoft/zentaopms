#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getExecution();
timeout=0
cid=1

- 检查获取数据的id属性id @3
- 检查获取数据的PM属性PM @admin
- 检查获取数据的burns第burns条的0属性 @35

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
