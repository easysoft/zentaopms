#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->gen(30);
zdTable('doclib')->config('doclib')->gen(15);
zdTable('doc')->config('doc')->gen(5);
zdTable('product')->gen(15);

/**

title=测试 actionModel->getDynamic();
cid=1
pid=1

查找全部动态       >> 90
查找用户admin动态  >> 30
查找用户dev17动态  >> 30
查找用户test18动态 >> 30
查找今天的动态     >> 2
查找昨天的动态     >> 2
查找上周的动态     >> 19
查找产品1的动态    >> 1
查找产品2的动态    >> 1
查找产品3的动态    >> 1
查找项目1的动态    >> 1
查找项目2的动态    >> 1
查找项目3的动态    >> 1
查找执行1的动态    >> 0
查找执行2的动态    >> 0
查找执行3的动态    >> 0
查找今天的动态     >> 88

*/

$accountList     = array('all', 'admin', 'dev17', 'test18');
$typeList        = array('all', 'today', 'yesterday', 'lastweek');
$productIDList   = array('all', 1, 2, 3);
$projectIDList   = array('all', 1, 2, 3);
$executionIDList = array('all', 1, 2, 3);
$dateList        = array('', 'today');

$action = new actionTest();

r($action->getDynamicTest())                                                                                                       && p() && e('30'); // 查找全部动态
r($action->getDynamicTest($accountList[1]))                                                                                        && p() && e('10');  // 查找用户admin动态
r($action->getDynamicTest($accountList[2]))                                                                                        && p() && e('10');  // 查找用户dev17动态
r($action->getDynamicTest($accountList[3]))                                                                                        && p() && e('10');  // 查找用户test18动态
r($action->getDynamicTest($accountList[0], $typeList[1]))                                                                          && p() && e('0');   // 查找今天的动态
r($action->getDynamicTest($accountList[0], $typeList[2]))                                                                          && p() && e('0');   // 查找昨天的动态
r($action->getDynamicTest($accountList[0], $typeList[3]))                                                                          && p() && e('6');  // 查找上周的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[1]))                                                       && p() && e('1');   // 查找产品1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[2]))                                                       && p() && e('1');   // 查找产品2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[3]))                                                       && p() && e('1');   // 查找产品3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[1]))                                    && p() && e('1');   // 查找项目1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[2]))                                    && p() && e('1');   // 查找项目2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[3]))                                    && p() && e('1');   // 查找项目3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[1]))               && p() && e('0');   // 查找执行1的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[2]))               && p() && e('0');   // 查找执行2的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[3]))               && p() && e('0');   // 查找执行3的动态
r($action->getDynamicTest($accountList[0], $typeList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], $dateList[1])) && p() && e('30');  // 查找今天的动态
