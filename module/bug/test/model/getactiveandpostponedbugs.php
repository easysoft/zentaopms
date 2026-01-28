#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('product')->loadYaml('product_type')->gen(10);
zenData('project')->loadYaml('project_type')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct_execution')->gen(10);
zenData('bug')->loadYaml('bug_status')->gen(10);

/**

title=bugModel->getActiveAndPostponedBugs();
timeout=0
cid=15354

- 查询普通产品1 2 执行1 下的 bug 数量 @3

- 查询普通产品1 2 执行7 下的 bug 数量 @0

- 查询多分支产品3 执行1 下的 bug 数量 @0

- 查询多分支产品3 执行7 下的 bug 数量 @2

- 查询普通产品1 2 执行1 下的 bug 的状态和分支
 - 第3条的status属性 @active
 - 第3条的branch属性 @0

- 查询多分支产品3 执行7 下的 bug7 的状态和分支
 - 第7条的status属性 @resolved
 - 第7条的resolution属性 @postponed
 - 第7条的branch属性 @0

- 查询多分支产品3 执行7 下的 bug8 的状态和分支
 - 第8条的status属性 @resolved
 - 第8条的resolution属性 @postponed
 - 第8条的branch属性 @1

*/

$productIDList = array(array(1, 2), array(3), array(1000001), array());
$executionList = array(1, 4, 7);

$bug = new bugModelTest();

r(count($bug->getActiveAndPostponedBugsTest($productIDList[0], $executionList[0]))) && p('') && e('3');                  // 查询普通产品1 2 执行1 下的 bug 数量
r(count($bug->getActiveAndPostponedBugsTest($productIDList[0], $executionList[2]))) && p('') && e('0');                  // 查询普通产品1 2 执行7 下的 bug 数量
r(count($bug->getActiveAndPostponedBugsTest($productIDList[1], $executionList[0]))) && p('') && e('0');                  // 查询多分支产品3 执行1 下的 bug 数量
r(count($bug->getActiveAndPostponedBugsTest($productIDList[1], $executionList[2]))) && p('') && e('2');                  // 查询多分支产品3 执行7 下的 bug 数量
r($bug->getActiveAndPostponedBugsTest($productIDList[0], $executionList[0])) && p('3:status,branch') && e('active,0');   // 查询普通产品1 2 执行1 下的 bug 的状态和分支
r($bug->getActiveAndPostponedBugsTest($productIDList[1], $executionList[2])) && p('7:status,resolution,branch') && e('resolved,postponed,0'); // 查询多分支产品3 执行7 下的 bug7 的状态和分支
r($bug->getActiveAndPostponedBugsTest($productIDList[1], $executionList[2])) && p('8:status,resolution,branch') && e('resolved,postponed,1'); // 查询多分支产品3 执行7 下的 bug8 的状态和分支