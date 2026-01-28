#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('module')->loadYaml('module')->gen('4');
zenData('scene')->loadYaml('modulebranchscene')->gen('8');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getSceneGroups();
timeout=0
cid=18997

- 产品 0、分支空、模块 0、正序，场景分组为空。 @0
- 产品 3、分支空、模块 0、正序，场景分组为空。 @0
- 产品 1、分支空、模块 0、正序，场景分组为空。 @0
- 产品 1、分支 1、模块 0、正序，场景分组为 1,2。 @1,2

- 产品 1、分支 1、模块 0、倒序，场景分组为 2,1。 @2,1

- 产品 1、分支 2、模块 0、倒序，场景分组为空。 @0
- 产品 1、分支 1、模块 6、倒序，场景分组为空。 @0
- 产品 1、分支 all、模块 0、倒序，场景分组为 6,5,4,3,2,1。 @6,5,4,3,2,1

- 产品 1、分支 all、模块 0、倒序，场景分组为 3,4,5,6,1,2。 @3,4,5,6,1,2

- 产品 1、分支 all、模块 0、倒序，场景分组为 3,6,1。 @3,6,1

*/

global $app;
$app->rawModule = 'testcase';
$app->rawMethod = 'getSceneGroups';

$testcase = new testcaseModelTest();
r($testcase->getSceneGroupsTest(0, '',    0, 'id_asc'))  && p() && e(0);             // 产品 0、分支空、模块 0、正序，场景分组为空。
r($testcase->getSceneGroupsTest(3, '',    0, 'id_asc'))  && p() && e(0);             // 产品 3、分支空、模块 0、正序，场景分组为空。
r($testcase->getSceneGroupsTest(1, '',    0, 'id_asc'))  && p() && e(0);             // 产品 1、分支空、模块 0、正序，场景分组为空。
r($testcase->getSceneGroupsTest(1, 1,     0, 'id_asc'))  && p() && e('1,2');         // 产品 1、分支 1、模块 0、正序，场景分组为 1,2。
r($testcase->getSceneGroupsTest(1, 1,     0, 'id_desc')) && p() && e('2,1');         // 产品 1、分支 1、模块 0、倒序，场景分组为 2,1。
r($testcase->getSceneGroupsTest(1, 2,     0, 'id_desc')) && p() && e(0);             // 产品 1、分支 2、模块 0、倒序，场景分组为空。
r($testcase->getSceneGroupsTest(1, 1,     6, 'id_desc')) && p() && e(0);             // 产品 1、分支 1、模块 6、倒序，场景分组为空。
r($testcase->getSceneGroupsTest(1, 'all', 0, 'id_desc')) && p() && e('6,5,4,3,2,1'); // 产品 1、分支 all、模块 0、倒序，场景分组为 6,5,4,3,2,1。
r($testcase->getSceneGroupsTest(1, 'all', 0, 'id_asc'))  && p() && e('3,4,5,6,1,2'); // 产品 1、分支 all、模块 0、倒序，场景分组为 3,4,5,6,1,2。
r($testcase->getSceneGroupsTest(1, 'all', 1, 'id_asc'))  && p() && e('3,6,1');       // 产品 1、分支 all、模块 0、倒序，场景分组为 3,6,1。