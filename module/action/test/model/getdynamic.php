#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('action')->loadYaml('action_year')->gen(35);
zenData('actionrecent')->loadYaml('action_year')->gen(35);
zenData('actionproduct')->loadYaml('actionproduct')->gen(35);
zenData('doclib')->gen(15);
zenData('doc')->gen(15);
zenData('product')->gen(5);
zenData('project')->loadYaml('execution')->gen(12);
zenData('user')->loadYaml('user')->gen(3);
zenData('userview')->loadYaml('userview')->gen(2);

/**

title=测试 actionModel->getDynamic();
timeout=0
cid=14893

- 查找全部动态 @35
- 查找用户admin动态 @12
- 查找用户dev17动态 @12
- 查找用户test18动态 @11
- 查找今天的动态 @1
- 查找昨天的动态 @1
- 查找上周的动态 @7
- 查找产品1的动态 @7
- 查找产品2的动态 @7
- 查找产品3的动态 @7
- 查找项目1的动态 @1
- 查找项目2的动态 @1
- 查找项目3的动态 @1
- 查找执行1的动态 @0
- 查找执行2的动态 @0
- 查找执行3的动态 @0
- 查找今天的动态 @0
- 查找全部动态 @35
- 查找用户admin动态 @12
- 查找用户dev17动态 @12
- 查找用户test18动态 @11
- 查找今天的动态 @5
- 查找昨天的动态 @0
- 查找上周的动态 @0
- 查找产品1的动态 @7
- 查找产品2的动态 @7
- 查找产品3的动态 @7
- 查找项目1的动态 @1
- 查找项目2的动态 @1
- 查找项目3的动态 @1
- 查找执行1的动态 @0
- 查找执行2的动态 @0
- 查找执行3的动态 @0
- 查找今天的动态 @0

*/

global $tester;
$tester->loadModel('action');
$tester->action->lang->SRCommon = '研发需求';
$tester->action->lang->URCommon = '用户需求';

$accountList     = array('all', 'admin', 'dev17', 'test18');
$typeList        = array('all', 'today', 'yesterday', 'lastweek');
$productIDList   = array('all', 1, 2, 3);
$projectIDList   = array('all', 1, 2, 3);
$executionIDList = array('all', 1, 2, 3);
$dateList        = array('', 'today');

$action = new actionModelTest();

global $app;
su('admin');
$app->user->rights['acls'] = array();
r($action->getDynamicTest())                                                                                                       && p() && e('35');  // 查找全部动态
r($action->getDynamicTest($accountList[1]))                                                                                        && p() && e('12');  // 查找用户admin动态
r($action->getDynamicTest($accountList[2]))                                                                                        && p() && e('12');  // 查找用户dev17动态
r($action->getDynamicTest($accountList[3]))                                                                                        && p() && e('11');  // 查找用户test18动态

zenData('action')->loadYaml('action_week')->gen(35, true, false);
zenData('actionrecent')->loadYaml('action_week')->gen(35, true, false);
r($action->getDynamicTest($accountList[0], $typeList[1]))                                                                          && p() && e('1');   // 查找今天的动态
r($action->getDynamicTest($accountList[0], $typeList[2]))                                                                          && p() && e('1');   // 查找昨天的动态
r($action->getDynamicTest($accountList[0], $typeList[3]))                                                                          && p() && e('7');   // 查找上周的动态

zenData('action')->loadYaml('action_year')->gen(35, true, false);
zenData('actionrecent')->loadYaml('action_year')->gen(35, true, false);
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[1]))                                                       && p() && e('7');   // 查找产品1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[2]))                                                       && p() && e('7');   // 查找产品2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[3]))                                                       && p() && e('7');   // 查找产品3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[1]))                                    && p() && e('1');   // 查找项目1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[2]))                                    && p() && e('1');   // 查找项目2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[3]))                                    && p() && e('1');   // 查找项目3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[1]))               && p() && e('0');   // 查找执行1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[2]))               && p() && e('0');   // 查找执行2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[3]))               && p() && e('0');   // 查找执行3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], $dateList[1])) && p() && e('0');   // 查找今天的动态

zenData('action')->loadYaml('action_year')->gen(35, true, false);
zenData('actionrecent')->loadYaml('action_year')->gen(35, true, false);
su('dev17');
$app->user->rights['acls'] = array();
r($action->getDynamicTest())                                                                                                       && p() && e('35');  // 查找全部动态
r($action->getDynamicTest($accountList[1]))                                                                                        && p() && e('12');  // 查找用户admin动态
r($action->getDynamicTest($accountList[2]))                                                                                        && p() && e('12');  // 查找用户dev17动态
r($action->getDynamicTest($accountList[3]))                                                                                        && p() && e('11');  // 查找用户test18动态

zenData('action')->loadYaml('action_year')->gen(35, true, false);
zenData('actionrecent')->loadYaml('action_year')->gen(35, true, false);
r($action->getDynamicTest($accountList[0], $typeList[1]))                                                                          && p() && e('5');   // 查找今天的动态
r($action->getDynamicTest($accountList[0], $typeList[2]))                                                                          && p() && e('0');   // 查找昨天的动态
r($action->getDynamicTest($accountList[0], $typeList[3]))                                                                          && p() && e('0');   // 查找上周的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[1]))                                                       && p() && e('7');   // 查找产品1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[2]))                                                       && p() && e('7');   // 查找产品2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[3]))                                                       && p() && e('7');   // 查找产品3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[1]))                                    && p() && e('1');   // 查找项目1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[2]))                                    && p() && e('1');   // 查找项目2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[3]))                                    && p() && e('1');   // 查找项目3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[1]))               && p() && e('0');   // 查找执行1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[2]))               && p() && e('0');   // 查找执行2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[3]))               && p() && e('0');   // 查找执行3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], $dateList[1])) && p() && e('0');   // 查找今天的动态
