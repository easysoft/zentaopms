#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

zenData('projectproduct')->loadYaml('projectproduct')->gen(30);
zenData('project')->loadYaml('execution')->gen(30);

/**

title=测试 projectModel->getGroupByProduct();
timeout=0
cid=17827

- 获取关联系统所有产品的项目信息
 - 第0条的product属性 @1
 - 第0条的name属性 @敏捷项目1
- 获取关联系统所有产品的未开始项目信息 @0
- 获取关联系统所有产品的进行中项目信息
 - 第0条的product属性 @1
 - 第0条的name属性 @敏捷项目1
- 获取关联系统所有产品的已关闭项目信息 @0
- 获取关联系统所有产品1、产品2、产品3的项目信息
 - 第0条的product属性 @1
 - 第0条的name属性 @敏捷项目1
- 获取关联系统所有产品1、产品2、产品3的未开始项目信息 @0
- 获取关联系统所有产品1、产品2、产品3的进行中项目信息
 - 第0条的product属性 @1
 - 第0条的name属性 @敏捷项目1
- 获取关联系统所有产品1、产品2、产品3的已关闭项目信息 @0
- 获取关联系统所有产品的项目数量 @4
- 获取关联系统所有产品的未开始项目数量 @0
- 获取关联系统所有产品的进行中项目数量 @4
- 获取关联系统所有产品的已关闭项目数量 @0
- 获取关联系统所有产品1、产品2、产品3的项目数量 @3
- 获取关联系统所有产品1、产品2、产品3的未开始项目数量 @0
- 获取关联系统所有产品1、产品2、产品3的进行中项目数量 @3
- 获取关联系统所有产品1、产品2、产品3的已关闭项目数量 @0

*/

$productIdList = array(array(), array(1, 2, 3));
$statusList    = array('', 'wait', 'doing', 'closed');

global $tester;
$tester->loadModel('project');
r(current($tester->project->getGroupByProduct($productIdList[0], $statusList[0]))) && p('0:product,name') && e('1,敏捷项目1'); // 获取关联系统所有产品的项目信息
r(current($tester->project->getGroupByProduct($productIdList[0], $statusList[1]))) && p()                 && e('0');           // 获取关联系统所有产品的未开始项目信息
r(current($tester->project->getGroupByProduct($productIdList[0], $statusList[2]))) && p('0:product,name') && e('1,敏捷项目1'); // 获取关联系统所有产品的进行中项目信息
r(current($tester->project->getGroupByProduct($productIdList[0], $statusList[3]))) && p()                 && e('0');           // 获取关联系统所有产品的已关闭项目信息
r(current($tester->project->getGroupByProduct($productIdList[1], $statusList[0]))) && p('0:product,name') && e('1,敏捷项目1'); // 获取关联系统所有产品1、产品2、产品3的项目信息
r(current($tester->project->getGroupByProduct($productIdList[1], $statusList[1]))) && p()                 && e('0');           // 获取关联系统所有产品1、产品2、产品3的未开始项目信息
r(current($tester->project->getGroupByProduct($productIdList[1], $statusList[2]))) && p('0:product,name') && e('1,敏捷项目1'); // 获取关联系统所有产品1、产品2、产品3的进行中项目信息
r(current($tester->project->getGroupByProduct($productIdList[1], $statusList[3]))) && p()                 && e('0');           // 获取关联系统所有产品1、产品2、产品3的已关闭项目信息

r(count($tester->project->getGroupByProduct($productIdList[0], $statusList[0]))) && p() && e('4'); // 获取关联系统所有产品的项目数量
r(count($tester->project->getGroupByProduct($productIdList[0], $statusList[1]))) && p() && e('0'); // 获取关联系统所有产品的未开始项目数量
r(count($tester->project->getGroupByProduct($productIdList[0], $statusList[2]))) && p() && e('4'); // 获取关联系统所有产品的进行中项目数量
r(count($tester->project->getGroupByProduct($productIdList[0], $statusList[3]))) && p() && e('0'); // 获取关联系统所有产品的已关闭项目数量
r(count($tester->project->getGroupByProduct($productIdList[1], $statusList[0]))) && p() && e('3'); // 获取关联系统所有产品1、产品2、产品3的项目数量
r(count($tester->project->getGroupByProduct($productIdList[1], $statusList[1]))) && p() && e('0'); // 获取关联系统所有产品1、产品2、产品3的未开始项目数量
r(count($tester->project->getGroupByProduct($productIdList[1], $statusList[2]))) && p() && e('3'); // 获取关联系统所有产品1、产品2、产品3的进行中项目数量
r(count($tester->project->getGroupByProduct($productIdList[1], $statusList[3]))) && p() && e('0'); // 获取关联系统所有产品1、产品2、产品3的已关闭项目数量