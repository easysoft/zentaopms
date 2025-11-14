#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/personnel.unittest.class.php';

zenData('project')->gen(50);
zenData('user')->gen(20);
zenData('product')->gen(10);
zenData('bug')->gen(50);
zenData('task')->gen(50);
zenData('team')->gen(50);

su('admin');

/**

title=测试 personnelModel->getInvest();
cid=17328

- 项目集1下投入人员admin创建的bug数量
 - 第qa[admin]条的role属性 @测试
 - 第qa[admin]条的createdBug属性 @3
- 项目集1下投入人员admin待处理的bug数量
 - 第qa[admin]条的role属性 @测试
 - 第qa[admin]条的pendingBug属性 @3
- 项目集1下投入人员admin创建的task数量
 - 第qa[admin]条的role属性 @测试
 - 第qa[admin]条的createdTask属性 @4
- 项目集1下投入人员admin待处理的task数量
 - 第qa[admin]条的role属性 @测试
 - 第qa[admin]条的createdTask属性 @4
- 项目集1下投入人员po53创建的bug数量
 - 第qa[user3]条的role属性 @测试
 - 第qa[user3]条的createdBug属性 @0
- 项目集1下投入人员po53待处理的bug数量
 - 第qa[user3]条的role属性 @测试
 - 第qa[user3]条的pendingBug属性 @0
- 项目集1下投入人员po53创建的任务数量
 - 第qa[user3]条的role属性 @测试
 - 第qa[user3]条的createdTask属性 @0
- 项目集1下投入人员po53完成的任务数量
 - 第qa[user3]条的role属性 @测试
 - 第qa[user3]条的finishedTask属性 @0

*/

$personnel = new personnelTest();
$programID = array(1, 2);

r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,createdBug')    && e('测试,3');  //项目集1下投入人员admin创建的bug数量
r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,pendingBug')    && e('测试,3');  //项目集1下投入人员admin待处理的bug数量
r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,createdTask')   && e('测试,4');  //项目集1下投入人员admin创建的task数量
r($personnel->getInvestTest($programID[0])) && p('qa[admin]:role,createdTask')   && e('测试,4');  //项目集1下投入人员admin待处理的task数量
r($personnel->getInvestTest($programID[1])) && p('qa[user3]:role,createdBug')    && e('测试,0');  //项目集1下投入人员po53创建的bug数量
r($personnel->getInvestTest($programID[1])) && p('qa[user3]:role,pendingBug')    && e('测试,0');  //项目集1下投入人员po53待处理的bug数量
r($personnel->getInvestTest($programID[1])) && p('qa[user3]:role,createdTask')   && e('测试,0');  //项目集1下投入人员po53创建的任务数量
r($personnel->getInvestTest($programID[1])) && p('qa[user3]:role,finishedTask')  && e('测试,0');  //项目集1下投入人员po53完成的任务数量
