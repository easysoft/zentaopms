#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getReviewBugs();
cid=1
pid=1



*/

$productIDList = array('1,2,3,1000001', '1,3');
$branch        = array('all', '1');
$moduleIDList  = array('1821,1825,1832,1000001', '1821, 1825');
$executions    = array(array(101,102), array(101,111));
$orderBy       = 'id_asc';

$bug=new bugTest();

r($bug->getReviewBugsTest($productIDList[0], $branch[0], $moduleIDList[0], $executions[0], $orderBy)) && p('') && e(''); // 查询产品1 2 3 不存在的产品10001 与分支all 模块1821, 1825, 1832 不存在的模块1000001 执行101 102下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[0], $branch[1], $moduleIDList[0], $executions[0], $orderBy)) && p('') && e(''); // 查询产品1 2 3 不存在的产品10001 与分支1   模块1821, 1825, 1832 不存在的模块1000001 执行101 102下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[0], $branch[0], $moduleIDList[1], $executions[0], $orderBy)) && p('') && e(''); // 查询产品1 2 3 不存在的产品10001 与分支all 模块1821, 1825  执行101 102下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[0], $branch[1], $moduleIDList[1], $executions[0], $orderBy)) && p('') && e(''); // 查询产品1 2 3 不存在的产品10001 与分支1   模块1821, 1825  执行101 102下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[0], $branch[0], $moduleIDList[0], $executions[1], $orderBy)) && p('') && e(''); // 查询产品1 2 3 不存在的产品10001 与分支all 模块1821, 1825, 1832 不存在的模块1000001 执行101 111下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[0], $branch[1], $moduleIDList[0], $executions[1], $orderBy)) && p('') && e(''); // 查询产品1 2 3 不存在的产品10001 与分支1   模块1821, 1825, 1832 不存在的模块1000001 执行101 111下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[0], $branch[0], $moduleIDList[1], $executions[1], $orderBy)) && p('') && e(''); // 查询产品1 2 3 不存在的产品10001 与分支all 模块1821, 1825  执行101 111下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[0], $branch[1], $moduleIDList[1], $executions[1], $orderBy)) && p('') && e(''); // 查询产品1 2 3 不存在的产品10001 与分支1   模块1821, 1825  执行101 111下审批人为当前用户的bug
//
r($bug->getReviewBugsTest($productIDList[1], $branch[0], $moduleIDList[0], $executions[0], $orderBy)) && p('') && e(''); // 查询产品1 3 与分支all 模块1821, 1825, 1832 不存在的模块1000001 执行101 102下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[1], $branch[1], $moduleIDList[0], $executions[0], $orderBy)) && p('') && e(''); // 查询产品1 3 与分支1   模块1821, 1825, 1832 不存在的模块1000001 执行101 102下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[1], $branch[0], $moduleIDList[1], $executions[0], $orderBy)) && p('') && e(''); // 查询产品1 3 与分支all 模块1821, 1825  执行101 102下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[1], $branch[1], $moduleIDList[1], $executions[0], $orderBy)) && p('') && e(''); // 查询产品1 3 与分支1   模块1821, 1825  执行101 102下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[1], $branch[0], $moduleIDList[0], $executions[1], $orderBy)) && p('') && e(''); // 查询产品1 3 与分支all 模块1821, 1825, 1832 不存在的模块1000001 执行101 111下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[1], $branch[1], $moduleIDList[0], $executions[1], $orderBy)) && p('') && e(''); // 查询产品1 3 与分支1   模块1821, 1825, 1832 不存在的模块1000001 执行101 111下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[1], $branch[0], $moduleIDList[1], $executions[1], $orderBy)) && p('') && e(''); // 查询产品1 3 与分支all 模块1821, 1825  执行101 111下审批人为当前用户的bug
r($bug->getReviewBugsTest($productIDList[1], $branch[1], $moduleIDList[1], $executions[1], $orderBy)) && p('') && e(''); // 查询产品1 3 与分支1   模块1821, 1825  执行101 111下审批人为当前用户的bug