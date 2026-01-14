#!/usr/bin/env php
<?php
/**

title=测试 projectModel->updateWhitelist();
cid=17883

- 测试敏捷项目添加白名单成员 @admin|user1|user2
- 测试瀑布项目添加白名单成员 @admin|user1|user2
- 测试看板项目添加白名单成员 @admin|user1|user2
- 测试敏捷项目替换白名单成员 @user2
- 测试瀑布项目替换白名单成员 @user2
- 测试看板项目替换白名单成员 @user2
- 测试敏捷项目删除白名单成员 @0
- 测试瀑布项目删除白名单成员 @0
- 测试看板项目删除白名单成员 @0
- 测试项目型敏捷项目添加白名单成员 @admin|user1|user2
- 测试项目型瀑布项目添加白名单成员 @admin|user1|user2
- 测试项目型看板项目添加白名单成员 @admin|user1|user2
- 测试项目型敏捷项目替换白名单成员 @user2
- 测试项目型瀑布项目替换白名单成员 @user2
- 测试项目型看板项目替换白名单成员 @user2
- 测试项目型敏捷项目删除白名单成员 @0
- 测试项目型瀑布项目删除白名单成员 @0
- 测试项目型看板项目删除白名单成员 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$projectTable = zenData('project')->loadYaml('project');
$projectTable->whitelist->range('`admin,user1`, `user1,user2`');
$projectTable->hasProduct->range('1{3},0{3}');
$projectTable->gen(6);

$projectproductTable = zenData('projectproduct');
$projectproductTable->project->range('1-6');
$projectproductTable->product->range('1-6');
$projectproductTable->gen(6);

$projectIdList = range(1, 6);
$whitelistList = array('admin,user1,user2', 'user2', '');

$projectTester = new projectModelTest();
r($projectTester->updateWhitelistTest($projectIdList[0], $whitelistList[0])) && p() && e('admin|user1|user2'); // 测试敏捷项目添加白名单成员
r($projectTester->updateWhitelistTest($projectIdList[1], $whitelistList[0])) && p() && e('admin|user1|user2'); // 测试瀑布项目添加白名单成员
r($projectTester->updateWhitelistTest($projectIdList[2], $whitelistList[0])) && p() && e('admin|user1|user2'); // 测试看板项目添加白名单成员
r($projectTester->updateWhitelistTest($projectIdList[0], $whitelistList[1])) && p() && e('user2');             // 测试敏捷项目替换白名单成员
r($projectTester->updateWhitelistTest($projectIdList[1], $whitelistList[1])) && p() && e('user2');             // 测试瀑布项目替换白名单成员
r($projectTester->updateWhitelistTest($projectIdList[2], $whitelistList[1])) && p() && e('user2');             // 测试看板项目替换白名单成员
r($projectTester->updateWhitelistTest($projectIdList[0], $whitelistList[2])) && p() && e('0');                 // 测试敏捷项目删除白名单成员
r($projectTester->updateWhitelistTest($projectIdList[1], $whitelistList[2])) && p() && e('0');                 // 测试瀑布项目删除白名单成员
r($projectTester->updateWhitelistTest($projectIdList[2], $whitelistList[2])) && p() && e('0');                 // 测试看板项目删除白名单成员
r($projectTester->updateWhitelistTest($projectIdList[3], $whitelistList[0])) && p() && e('admin|user1|user2'); // 测试项目型敏捷项目添加白名单成员
r($projectTester->updateWhitelistTest($projectIdList[4], $whitelistList[0])) && p() && e('admin|user1|user2'); // 测试项目型瀑布项目添加白名单成员
r($projectTester->updateWhitelistTest($projectIdList[5], $whitelistList[0])) && p() && e('admin|user1|user2'); // 测试项目型看板项目添加白名单成员
r($projectTester->updateWhitelistTest($projectIdList[3], $whitelistList[1])) && p() && e('user2');             // 测试项目型敏捷项目替换白名单成员
r($projectTester->updateWhitelistTest($projectIdList[4], $whitelistList[1])) && p() && e('user2');             // 测试项目型瀑布项目替换白名单成员
r($projectTester->updateWhitelistTest($projectIdList[5], $whitelistList[1])) && p() && e('user2');             // 测试项目型看板项目替换白名单成员
r($projectTester->updateWhitelistTest($projectIdList[3], $whitelistList[2])) && p() && e('0');                 // 测试项目型敏捷项目删除白名单成员
r($projectTester->updateWhitelistTest($projectIdList[4], $whitelistList[2])) && p() && e('0');                 // 测试项目型瀑布项目删除白名单成员
r($projectTester->updateWhitelistTest($projectIdList[5], $whitelistList[2])) && p() && e('0');                 // 测试项目型看板项目删除白名单成员
