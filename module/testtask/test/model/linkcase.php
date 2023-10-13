#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testtask.class.php';
su('admin');

zdTable('testtask')->config('testtask')->gen(16);
zdTable('testrun')->config('testrun')->gen(4);
zdTable('projectcase')->gen(0);
zdTable('action')->gen(0);

/**

title=测试 testtaskModel->linkCase();
cid=1
pid=1

*/

$testtask  = new testtaskTest();
$taskModel = $testtask->objectModel;

$case1  = (object)array('case' => 1, 'version' => 1);
$case2  = (object)array('case' => 2, 'version' => 1);
$case3  = (object)array('case' => 3, 'version' => 1);
$case4  = (object)array('case' => 4, 'version' => 1);
$cases1 = array($case1, $case2);
$cases2 = array($case3, $case4);

$_SESSION['product']   = 1; // 产品 1
$_SESSION['project']   = 1; // 项目 1
$_SESSION['execution'] = 2; // 执行 2

global $app;
$app->tab = 'qa'; // 在测试应用中关联用例。

r($testtask->linkCaseTest(1, 'all', array())) && p() && e(0); // 要关联到当前测试单的用例为空数组时 返回 false。

$result = $testtask->linkCaseTest(2, 'all', $cases1);
r($result['runs'])    && p('0:task,case,version,assignedTo,status') && e('2,2,1,~~,normal');          // 在测试应用中全部用例标签下关联用例 1,2 到测试单 2，查看关联后的测试单 2 中的用例 2。
r($result['runs'])    && p('1:task,case,version,assignedTo,status') && e('2,1,1,~~,normal');          // 在测试应用中全部用例标签下关联用例 1,2 到测试单 2，查看关联后的测试单 2 中的用例 1。
r($result['actions']) && p('0:objectType,objectID,action,extra')    && e('case,2,linked2testtask,2'); // 在测试应用中全部用例标签下关联用例 1,2 到测试单 2，查看关联后的用例 2 的日志。
r($result['actions']) && p('1:objectType,objectID,action,extra')    && e('case,1,linked2testtask,2'); // 在测试应用中全部用例标签下关联用例 1,2 到测试单 2，查看关联后的用例 1 的日志。

$result = $testtask->linkCaseTest(3, 'bystory', $cases1);
r($result['runs'])    && p('0:task,case,version,assignedTo,status') && e('3,2,1,~~,normal');          // 在测试应用中按研发需求关联标签下关联用例 1,2 到测试单 3，查看关联后的测试单 3 中的用例
r($result['runs'])    && p('1:task,case,version,assignedTo,status') && e('3,1,1,~~,normal');          // 在测试应用中按研发需求关联标签下关联用例 1,2 到测试单 3，查看关联后的测试单 3 中的用例
r($result['actions']) && p('0:objectType,objectID,action,extra')    && e('case,2,linked2testtask,3'); // 在测试应用中按研发需求关联标签下关联用例 1,2 到测试单 3，查看关联后的用例 2 的日志。
r($result['actions']) && p('1:objectType,objectID,action,extra')    && e('case,1,linked2testtask,3'); // 在测试应用中按研发需求关联标签下关联用例 1,2 到测试单 3，查看关联后的用例 1 的日志。

$result = $testtask->linkCaseTest(4, 'bysuite', $cases1);
r($result['runs'])    && p('0:task,case,version,assignedTo,status') && e('4,2,1,~~,normal');          // 在测试应用中按套件关联标签下关联用例 1,2 到测试单 4，查看关联后的测试单 4 中的用例 2。
r($result['runs'])    && p('1:task,case,version,assignedTo,status') && e('4,1,1,~~,normal');          // 在测试应用中按套件关联标签下关联用例 1,2 到测试单 4，查看关联后的测试单 4 中的用例 1。
r($result['actions']) && p('0:objectType,objectID,action,extra')    && e('case,2,linked2testtask,4'); // 在测试应用中按套件关联标签下关联用例 1,2 到测试单 4，查看关联后的用例 2 的日志。
r($result['actions']) && p('1:objectType,objectID,action,extra')    && e('case,1,linked2testtask,4'); // 在测试应用中按套件关联标签下关联用例 1,2 到测试单 4，查看关联后的用例 1 的日志。

$result = $testtask->linkCaseTest(5, 'bybuild', $cases2);
r($result['runs'])    && p('0:task,case,version,assignedTo,status') && e('5,4,1,dev4,normal');        // 在测试应用中复制测试单标签下关联用例 3,4 到测试单 5，查看关联后的测试单 5 中的用例 4。
r($result['runs'])    && p('1:task,case,version,assignedTo,status') && e('5,3,1,test3,normal');       // 在测试应用中复制测试单标签下关联用例 3,4 到测试单 5，查看关联后的测试单 5 中的用例 3。
r($result['actions']) && p('0:objectType,objectID,action,extra')    && e('case,4,linked2testtask,5'); // 在测试应用中复制测试单标签下关联用例 3,4 到测试单 5，查看关联后的用例 4 的日志。
r($result['actions']) && p('1:objectType,objectID,action,extra')    && e('case,3,linked2testtask,5'); // 在测试应用中复制测试单标签下关联用例 3,4 到测试单 5，查看关联后的用例 3 的日志。

$result = $testtask->linkCaseTest(6, 'bybug', $cases2);
r($result['runs'])    && p('0:task,case,version,assignedTo,status') && e('6,4,1,~~,normal');          // 在测试应用中按缺陷关联标签下关联用例 3,4 到测试单 6，查看关联后的测试单 6 中的用例 4。
r($result['runs'])    && p('1:task,case,version,assignedTo,status') && e('6,3,1,~~,normal');          // 在测试应用中按缺陷关联标签下关联用例 3,4 到测试单 6，查看关联后的测试单 6 中的用例 3。
r($result['actions']) && p('0:objectType,objectID,action,extra')    && e('case,4,linked2testtask,6'); // 在测试应用中按缺陷关联标签下关联用例 3,4 到测试单 6，查看关联后的用例 4 的日志。
r($result['actions']) && p('1:objectType,objectID,action,extra')    && e('case,3,linked2testtask,6'); // 在测试应用中按缺陷关联标签下关联用例 3,4 到测试单 6，查看关联后的用例 3 的日志。

$app->tab = 'project'; // 在项目应用中关联用例。

$result = $testtask->linkCaseTest(7, 'all', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('1,1,1,1,1'); // 在项目 1 中全部用例标签下关联用例 1,2 到测试单 7，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('1,1,2,1,2'); // 在项目 1 中全部用例标签下关联用例 1,2 到测试单 7，查看关联后的项目 1 中的用例 2。

$result = $testtask->linkCaseTest(8, 'bystory', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('1,1,1,1,3'); // 在项目 1 中按研发需求关联标签下关联用例 1,2 到测试单 8，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('1,1,2,1,4'); // 在项目 1 中按研发需求关联标签下关联用例 1,2 到测试单 8，查看关联后的项目 1 中的用例 2。

$result = $testtask->linkCaseTest(9, 'bysuite', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('1,1,1,1,5'); // 在项目 1 中按套件关联标签下关联用例 1,2 到测试单 9，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('1,1,2,1,6'); // 在项目 1 中按套件关联标签下关联用例 1,2 到测试单 9，查看关联后的项目 1 中的用例 2。

$result = $testtask->linkCaseTest(10, 'bybuild', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('1,1,1,1,7'); // 在项目 1 中复制测试单标签下关联用例 1,2 到测试单 10，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('1,1,2,1,8'); // 在项目 1 中复制测试单标签下关联用例 1,2 到测试单 10，查看关联后的项目 1 中的用例 2。

$result = $testtask->linkCaseTest(11, 'bybug', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('1,1,1,1,9');  // 在项目 1 中按缺陷关联标签下关联用例 1,2 到测试单 11，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('1,1,2,1,10'); // 在项目 1 中按缺陷关联标签下关联用例 1,2 到测试单 11，查看关联后的项目 1 中的用例 2。

$app->tab = 'execution'; // 在执行应用中关联用例。

$result = $testtask->linkCaseTest(12, 'all', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('2,1,1,1,1'); // 在执行 2 中全部用例标签下关联用例 1,2 到测试单 12，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('2,1,2,1,2'); // 在执行 2 中全部用例标签下关联用例 1,2 到测试单 12，查看关联后的执行 2 中的用例 2。

$result = $testtask->linkCaseTest(13, 'bystory', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('2,1,1,1,3'); // 在执行 2 中按研发需求关联标签下关联用例 1,2 到测试单 13，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('2,1,2,1,4'); // 在执行 2 中按研发需求关联标签下关联用例 1,2 到测试单 13，查看关联后的执行 2 中的用例 2。

$result = $testtask->linkCaseTest(14, 'bysuite', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('2,1,1,1,5'); // 在执行 2 中按套件关联标签下关联用例 1,2 到测试单 14，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('2,1,2,1,6'); // 在执行 2 中按套件关联标签下关联用例 1,2 到测试单 14，查看关联后的执行 2 中的用例 2。

$result = $testtask->linkCaseTest(15, 'bybuild', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('2,1,1,1,7'); // 在执行 2 中复制测试单标签下关联用例 1,2 到测试单 15，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('2,1,2,1,8'); // 在执行 2 中复制测试单标签下关联用例 1,2 到测试单 15，查看关联后的执行 2 中的用例 2。

$result = $testtask->linkCaseTest(16, 'bybug', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('2,1,1,1,9');  // 在执行 2 中按缺陷关联标签下关联用例 1,2 到测试单 16，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('2,1,2,1,10'); // 在执行 2 中按缺陷关联标签下关联用例 1,2 到测试单 16，查看关联后的执行 2 中的用例 2。
