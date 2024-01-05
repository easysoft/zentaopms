#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->config('action')->gen(25);
zdTable('product')->gen(1);
zdTable('story')->gen(1);
zdTable('productplan')->gen(1);
zdTable('release')->gen(1);
zdTable('project')->gen(1);
zdTable('task')->gen(1);
zdTable('build')->config('build')->gen(2);
zdTable('bug')->gen(1);
zdTable('case')->gen(2);
zdTable('testtask')->gen(1);
zdTable('branch')->gen(1);
zdTable('module')->gen(1);
zdTable('testsuite')->config('testsuite')->gen(2);
zdTable('testreport')->gen(1);
zdTable('entry')->config('entry')->gen(1);
zdTable('webhook')->config('webhook')->gen(1);
zdTable('doclib')->gen(10);
zdTable('user')->gen(10);
zdTable('doc')->gen(10);
zdTable('todo')->gen(10);

su('admin');

/**

title=测试 actionModel->getRelatedDataByActions();
timeout=0
cid=1

- 获取动态1 2 3 4 5 objectNames的关联信息
 - 第product条的1属性 @正常产品1
 - 第story条的1属性 @用户需求1
 - 第productplan条的1属性 @1.0
 - 第release条的1属性 @产品正常的正常的发布1
 - 第project条的1属性 @项目集1
 - 第user条的0属性 @guest
- 获取动态1 2 3 4 5 relatedProjects的关联信息第project条的1属性 @0
- 获取动态1 2 3 4 5 requirements的关联信息属性1 @1
- 获取动态6 7 8 9 10 objectNames的关联信息
 - 第task条的1属性 @开发任务11
 - 第build条的1属性 @项目11版本1
 - 第bug条的1属性 @BUG1
 - 第testcase条的1属性 @这个是测试用例1
 - 第case条的1属性 @这个是测试用例1
 - 第user条的0属性 @guest
- 获取动态1 2 3 4 5 relatedProjects的关联信息第task条的1属性 @11
- 获取动态6 7 8 9 10 requirements的关联信息 @0
- 获取动态11 12 13 14 15 objectNames的关联信息
 - 第testtask条的1属性 @测试单1
 - 第doc条的1属性 @文档标题1
 - 第doclib条的1属性 @产品主库
 - 第todo条的1属性 @自定义1的待办
 - 第user条的0属性 @guest
 - 第user条的1属性 @admin
- 获取动态1 2 3 4 5 relatedProjects的关联信息第testtask条的1属性 @11
- 获取动态11 12 13 14 15 requirements的关联信息 @0
- 获取动态16 17 18 19 20 objectNames的关联信息
 - 第branch条的1属性 @分支1
 - 第module条的1属性 @这是一个模块1
 - 第testsuite条的1属性 @这是测试套件名称1
 - 第caselib条的2属性 @这是测试套件名称2
 - 第user条的0属性 @guest
- 获取动态16 17 18 19 20 relatedProjects的关联信息第testreport条的1属性 @11
- 获取动态16 17 18 19 20 requirements的关联信息 @0
- 获取动态21 22 23 24 25 objectNames的关联信息
 - 第entry条的1属性 @这是应用名称1
 - 第webhook条的1属性 @钉钉群机器人
 - 第product条的1属性 @正常产品1
 - 第story条的1属性 @用户需求1
 - 第user条的0属性 @guest
- 获取动态21 22 23 24 25 relatedProjects的关联信息第story条的0属性 @~~
- 获取动态21 22 23 24 25 requirements的关联信息属性1 @1

*/

$actions = array('1,2,3,4,5', '6,7,8,9,10', '11,12,13,14,15', '16,17,18,19,20', '21,22,23,24,25');

$action = new actionTest();

r($action->getRelatedDataByActionsTest($actions[0], 'objectNames'))     && p('product:1;story:1;productplan:1;release:1;project:1;user:0') && e('正常产品1;用户需求1;1.0;产品正常的正常的发布1;项目集1;guest');         // 获取动态1 2 3 4 5 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[0], 'relatedProjects')) && p('project:1')                                                  && e('0');                                                                   // 获取动态1 2 3 4 5 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[0], 'requirements'))    && p('1')                                                          && e('1');                                                                   // 获取动态1 2 3 4 5 requirements的关联信息
r($action->getRelatedDataByActionsTest($actions[1], 'objectNames'))     && p('task:1;build:1;bug:1;testcase:1;case:1;user:0')              && e('开发任务11;项目11版本1;BUG1;这个是测试用例1;这个是测试用例1;guest'); // 获取动态6 7 8 9 10 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[1], 'relatedProjects')) && p('task:1')                                                     && e('11');                                                                  // 获取动态1 2 3 4 5 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[1], 'requirements'))    && p()                                                             && e('0');                                                                   // 获取动态6 7 8 9 10 requirements的关联信息
r($action->getRelatedDataByActionsTest($actions[2], 'objectNames'))     && p('testtask:1;doc:1;doclib:1;todo:1;user:0,1')                  && e('测试单1;文档标题1;产品主库;自定义1的待办;guest,admin');                // 获取动态11 12 13 14 15 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[2], 'relatedProjects')) && p('testtask:1')                                                 && e('11');                                                                  // 获取动态1 2 3 4 5 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[2], 'requirements'))    && p()                                                             && e('0');                                                                   // 获取动态11 12 13 14 15 requirements的关联信息
r($action->getRelatedDataByActionsTest($actions[3], 'objectNames'))     && p('branch:1;module:1;testsuite:1;caselib:2;user:0')             && e('分支1;这是一个模块1;这是测试套件名称1;这是测试套件名称2;guest');       // 获取动态16 17 18 19 20 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[3], 'relatedProjects')) && p('testreport:1')                                               && e('11');                                                                  // 获取动态16 17 18 19 20 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[3], 'requirements'))    && p()                                                             && e('0');                                                                   // 获取动态16 17 18 19 20 requirements的关联信息
r($action->getRelatedDataByActionsTest($actions[4], 'objectNames'))     && p('entry:1;webhook:1;product:1;story:1;user:0')                 && e('这是应用名称1;钉钉群机器人;正常产品1;用户需求1;guest');                // 获取动态21 22 23 24 25 objectNames的关联信息
r($action->getRelatedDataByActionsTest($actions[4], 'relatedProjects')) && p('story:0')                                                    && e('~~');                                                                  // 获取动态21 22 23 24 25 relatedProjects的关联信息
r($action->getRelatedDataByActionsTest($actions[4], 'requirements'))    && p('1')                                                          && e('1');                                                                   // 获取动态21 22 23 24 25 requirements的关联信息