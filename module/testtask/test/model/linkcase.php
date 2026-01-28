#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(1);
zenData('testrun')->loadYaml('testrun')->gen(4);
zenData('projectcase')->gen(0);
zenData('action')->gen(0);

$testtaskData = zenData('testtask');
$testtaskData->execution->range(101);
$testtaskData->loadYaml('testtask')->gen(16);

/**

title=测试 testtaskModel->linkCase();
timeout=0
cid=19208

- 要关联到当前测试单的用例为空数组时 返回 false。 @0
- 在测试应用中全部用例标签下关联用例 1,2 到测试单 2，查看关联后的测试单 2 中的用例 2。
 - 第0条的task属性 @2
 - 第0条的case属性 @2
 - 第0条的version属性 @1
 - 第0条的assignedTo属性 @~~
 - 第0条的status属性 @normal
- 在测试应用中全部用例标签下关联用例 1,2 到测试单 2，查看关联后的测试单 2 中的用例 1。
 - 第1条的task属性 @2
 - 第1条的case属性 @1
 - 第1条的version属性 @1
 - 第1条的assignedTo属性 @~~
 - 第1条的status属性 @normal
- 在测试应用中全部用例标签下关联用例 1,2 到测试单 2，查看关联后的用例 2 的日志。
 - 第0条的objectType属性 @case
 - 第0条的objectID属性 @2
 - 第0条的action属性 @linked2testtask
 - 第0条的extra属性 @2
- 在测试应用中全部用例标签下关联用例 1,2 到测试单 2，查看关联后的用例 1 的日志。
 - 第1条的objectType属性 @case
 - 第1条的objectID属性 @1
 - 第1条的action属性 @linked2testtask
 - 第1条的extra属性 @2
- 在测试应用中按研发需求关联标签下关联用例 1,2 到测试单 3，查看关联后的测试单 3 中的用例
 - 第0条的task属性 @3
 - 第0条的case属性 @2
 - 第0条的version属性 @1
 - 第0条的assignedTo属性 @~~
 - 第0条的status属性 @normal
- 在测试应用中按研发需求关联标签下关联用例 1,2 到测试单 3，查看关联后的测试单 3 中的用例
 - 第1条的task属性 @3
 - 第1条的case属性 @1
 - 第1条的version属性 @1
 - 第1条的assignedTo属性 @~~
 - 第1条的status属性 @normal
- 在测试应用中按研发需求关联标签下关联用例 1,2 到测试单 3，查看关联后的用例 2 的日志。
 - 第0条的objectType属性 @case
 - 第0条的objectID属性 @2
 - 第0条的action属性 @linked2testtask
 - 第0条的extra属性 @3
- 在测试应用中按研发需求关联标签下关联用例 1,2 到测试单 3，查看关联后的用例 1 的日志。
 - 第1条的objectType属性 @case
 - 第1条的objectID属性 @1
 - 第1条的action属性 @linked2testtask
 - 第1条的extra属性 @3
- 在测试应用中按套件关联标签下关联用例 1,2 到测试单 4，查看关联后的测试单 4 中的用例 2。
 - 第0条的task属性 @4
 - 第0条的case属性 @2
 - 第0条的version属性 @1
 - 第0条的assignedTo属性 @~~
 - 第0条的status属性 @normal
- 在测试应用中按套件关联标签下关联用例 1,2 到测试单 4，查看关联后的测试单 4 中的用例 1。
 - 第1条的task属性 @4
 - 第1条的case属性 @1
 - 第1条的version属性 @1
 - 第1条的assignedTo属性 @~~
 - 第1条的status属性 @normal
- 在测试应用中按套件关联标签下关联用例 1,2 到测试单 4，查看关联后的用例 2 的日志。
 - 第0条的objectType属性 @case
 - 第0条的objectID属性 @2
 - 第0条的action属性 @linked2testtask
 - 第0条的extra属性 @4
- 在测试应用中按套件关联标签下关联用例 1,2 到测试单 4，查看关联后的用例 1 的日志。
 - 第1条的objectType属性 @case
 - 第1条的objectID属性 @1
 - 第1条的action属性 @linked2testtask
 - 第1条的extra属性 @4
- 在测试应用中复制测试单标签下关联用例 3,4 到测试单 5，查看关联后的测试单 5 中的用例 4。
 - 第0条的task属性 @5
 - 第0条的case属性 @4
 - 第0条的version属性 @1
 - 第0条的assignedTo属性 @dev4
 - 第0条的status属性 @normal
- 在测试应用中复制测试单标签下关联用例 3,4 到测试单 5，查看关联后的测试单 5 中的用例 3。
 - 第1条的task属性 @5
 - 第1条的case属性 @3
 - 第1条的version属性 @1
 - 第1条的assignedTo属性 @test3
 - 第1条的status属性 @normal
- 在测试应用中复制测试单标签下关联用例 3,4 到测试单 5，查看关联后的用例 4 的日志。
 - 第0条的objectType属性 @case
 - 第0条的objectID属性 @4
 - 第0条的action属性 @linked2testtask
 - 第0条的extra属性 @5
- 在测试应用中复制测试单标签下关联用例 3,4 到测试单 5，查看关联后的用例 3 的日志。
 - 第1条的objectType属性 @case
 - 第1条的objectID属性 @3
 - 第1条的action属性 @linked2testtask
 - 第1条的extra属性 @5
- 在测试应用中按缺陷关联标签下关联用例 3,4 到测试单 6，查看关联后的测试单 6 中的用例 4。
 - 第0条的task属性 @6
 - 第0条的case属性 @4
 - 第0条的version属性 @1
 - 第0条的assignedTo属性 @~~
 - 第0条的status属性 @normal
- 在测试应用中按缺陷关联标签下关联用例 3,4 到测试单 6，查看关联后的测试单 6 中的用例 3。
 - 第1条的task属性 @6
 - 第1条的case属性 @3
 - 第1条的version属性 @1
 - 第1条的assignedTo属性 @~~
 - 第1条的status属性 @normal
- 在测试应用中按缺陷关联标签下关联用例 3,4 到测试单 6，查看关联后的用例 4 的日志。
 - 第0条的objectType属性 @case
 - 第0条的objectID属性 @4
 - 第0条的action属性 @linked2testtask
 - 第0条的extra属性 @6
- 在测试应用中按缺陷关联标签下关联用例 3,4 到测试单 6，查看关联后的用例 3 的日志。
 - 第1条的objectType属性 @case
 - 第1条的objectID属性 @3
 - 第1条的action属性 @linked2testtask
 - 第1条的extra属性 @6
- 在项目 1 中全部用例标签下关联用例 1,2 到测试单 7，查看关联后的项目 1 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @11
- 在项目 1 中全部用例标签下关联用例 1,2 到测试单 7，查看关联后的项目 1 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @12
- 在项目 1 中按研发需求关联标签下关联用例 1,2 到测试单 8，查看关联后的项目 1 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @13
- 在项目 1 中按研发需求关联标签下关联用例 1,2 到测试单 8，查看关联后的项目 1 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @14
- 在项目 1 中按套件关联标签下关联用例 1,2 到测试单 9，查看关联后的项目 1 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @15
- 在项目 1 中按套件关联标签下关联用例 1,2 到测试单 9，查看关联后的项目 1 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @16
- 在项目 1 中复制测试单标签下关联用例 1,2 到测试单 10，查看关联后的项目 1 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @17
- 在项目 1 中复制测试单标签下关联用例 1,2 到测试单 10，查看关联后的项目 1 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @18
- 在项目 1 中按缺陷关联标签下关联用例 1,2 到测试单 11，查看关联后的项目 1 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @19
- 在项目 1 中按缺陷关联标签下关联用例 1,2 到测试单 11，查看关联后的项目 1 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @20
- 在执行 2 中全部用例标签下关联用例 1,2 到测试单 12，查看关联后的执行 2 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @21
- 在执行 2 中全部用例标签下关联用例 1,2 到测试单 12，查看关联后的执行 2 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @22
- 在执行 2 中按研发需求关联标签下关联用例 1,2 到测试单 13，查看关联后的执行 2 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @23
- 在执行 2 中按研发需求关联标签下关联用例 1,2 到测试单 13，查看关联后的执行 2 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @24
- 在执行 2 中按套件关联标签下关联用例 1,2 到测试单 14，查看关联后的执行 2 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @25
- 在执行 2 中按套件关联标签下关联用例 1,2 到测试单 14，查看关联后的执行 2 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @26
- 在执行 2 中复制测试单标签下关联用例 1,2 到测试单 15，查看关联后的执行 2 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @27
- 在执行 2 中复制测试单标签下关联用例 1,2 到测试单 15，查看关联后的执行 2 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @28
- 在执行 2 中按缺陷关联标签下关联用例 1,2 到测试单 16，查看关联后的执行 2 中的用例 1。
 - 第0条的project属性 @101
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第0条的version属性 @1
 - 第0条的order属性 @29
- 在执行 2 中按缺陷关联标签下关联用例 1,2 到测试单 16，查看关联后的执行 2 中的用例 2。
 - 第1条的project属性 @101
 - 第1条的product属性 @1
 - 第1条的case属性 @2
 - 第1条的version属性 @1
 - 第1条的order属性 @30

*/

$testtask  = new testtaskModelTest();
$taskModel = $testtask->objectModel;

$case1  = (object)array('case' => 1, 'version' => 1);
$case2  = (object)array('case' => 2, 'version' => 1);
$case3  = (object)array('case' => 3, 'version' => 1);
$case4  = (object)array('case' => 4, 'version' => 1);
$cases1 = array($case1, $case2);
$cases2 = array($case3, $case4);

$_SESSION['product']   = 1; // 产品 1
$_SESSION['project']   = 1; // 项目 1
$_SESSION['execution'] = 101; // 执行 101

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
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,11'); // 在项目 1 中全部用例标签下关联用例 1,2 到测试单 7，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,12'); // 在项目 1 中全部用例标签下关联用例 1,2 到测试单 7，查看关联后的项目 1 中的用例 2。

$result = $testtask->linkCaseTest(8, 'bystory', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,13'); // 在项目 1 中按研发需求关联标签下关联用例 1,2 到测试单 8，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,14'); // 在项目 1 中按研发需求关联标签下关联用例 1,2 到测试单 8，查看关联后的项目 1 中的用例 2。

$result = $testtask->linkCaseTest(9, 'bysuite', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,15'); // 在项目 1 中按套件关联标签下关联用例 1,2 到测试单 9，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,16'); // 在项目 1 中按套件关联标签下关联用例 1,2 到测试单 9，查看关联后的项目 1 中的用例 2。

$result = $testtask->linkCaseTest(10, 'bybuild', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,17'); // 在项目 1 中复制测试单标签下关联用例 1,2 到测试单 10，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,18'); // 在项目 1 中复制测试单标签下关联用例 1,2 到测试单 10，查看关联后的项目 1 中的用例 2。

$result = $testtask->linkCaseTest(11, 'bybug', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,19');  // 在项目 1 中按缺陷关联标签下关联用例 1,2 到测试单 11，查看关联后的项目 1 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,20'); // 在项目 1 中按缺陷关联标签下关联用例 1,2 到测试单 11，查看关联后的项目 1 中的用例 2。

$app->tab = 'execution'; // 在执行应用中关联用例。

$result = $testtask->linkCaseTest(12, 'all', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,21'); // 在执行 2 中全部用例标签下关联用例 1,2 到测试单 12，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,22'); // 在执行 2 中全部用例标签下关联用例 1,2 到测试单 12，查看关联后的执行 2 中的用例 2。

$result = $testtask->linkCaseTest(13, 'bystory', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,23'); // 在执行 2 中按研发需求关联标签下关联用例 1,2 到测试单 13，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,24'); // 在执行 2 中按研发需求关联标签下关联用例 1,2 到测试单 13，查看关联后的执行 2 中的用例 2。

$result = $testtask->linkCaseTest(14, 'bysuite', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,25'); // 在执行 2 中按套件关联标签下关联用例 1,2 到测试单 14，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,26'); // 在执行 2 中按套件关联标签下关联用例 1,2 到测试单 14，查看关联后的执行 2 中的用例 2。

$result = $testtask->linkCaseTest(15, 'bybuild', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,27'); // 在执行 2 中复制测试单标签下关联用例 1,2 到测试单 15，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,28'); // 在执行 2 中复制测试单标签下关联用例 1,2 到测试单 15，查看关联后的执行 2 中的用例 2。

$result = $testtask->linkCaseTest(16, 'bybug', $cases1);
r($result['cases']) && p('0:project,product,case,version,order') && e('101,1,1,1,29');  // 在执行 2 中按缺陷关联标签下关联用例 1,2 到测试单 16，查看关联后的执行 2 中的用例 1。
r($result['cases']) && p('1:project,product,case,version,order') && e('101,1,2,1,30'); // 在执行 2 中按缺陷关联标签下关联用例 1,2 到测试单 16，查看关联后的执行 2 中的用例 2。
