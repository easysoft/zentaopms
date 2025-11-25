#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

zenData('project')->loadYaml('execution', true)->gen(30);

/**

title=测试 executionModel->getExecutionList();
timeout=0
cid=17824

- 测试空数据 @0
- 测试获取项目下执行的数量 @17
- 测试获取项目下执行的信息第116条的name属性 @阶段20
- 测试获取项目下执行的信息第117条的name属性 @阶段21
- 测试获取不存在项目下的执行 @0

*/

global $tester;
$projectModel = $tester->loadModel('project');

r($projectModel->getExecutionList())                     && p()           && e('0');      // 测试空数据
r(count($projectModel->getExecutionList(array(11, 60)))) && p()           && e('17');     // 测试获取项目下执行的数量
r($projectModel->getExecutionList(array(11, 60)))        && p('116:name') && e('阶段20'); // 测试获取项目下执行的信息
r($projectModel->getExecutionList(array(11, 60)))        && p('117:name') && e('阶段21'); // 测试获取项目下执行的信息
r($projectModel->getExecutionList(array(1)))             && p()           && e('0');      // 测试获取不存在项目下的执行