#!/usr/bin/env php
<?php
/**

title=测试 projectZen::processProjectListData();
timeout=0
cid=17959

- 获取处理后的项目数据
 - 第1条的name属性 @瀑布项目2
 - 第1条的budget属性 @$ 79.99万
 - 第1条的storyPoints属性 @0 h
 - 第1条的estimate属性 @0
 - 第1条的consume属性 @0
 - 第1条的left属性 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';
zenData('project')->loadYaml('project')->gen(5);
zenData('product')->loadYaml('product')->gen(5);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);
zenData('task')->loadYaml('task')->gen(10);
zenData('effort')->loadYaml('effort')->gen(10);
zenData('branch')->gen(0);
zenData('story')->gen(0);
zenData('user')->gen(5);
su('admin');

global $tester;
$program  = $tester->loadModel('program');
$list     = $program->getProjectStats(0, 'all', 0, 'id_asc');
$firstId  = empty($list) ? 0 : key($list);
if($firstId) $tester->dao->update(TABLE_PROJECT)->set('name')->eq('瀑布项目2')->set('budget')->eq(799900)->set('budgetUnit')->eq('USD')->where('id')->eq($firstId)->exec();

$projectTester = new projectZenTest();
$result = $projectTester->processProjectListDataTest();
r($result) && p('0:name,budget,storyPoints,estimate,consume,left') && e('瀑布项目2,$ 79.99万,0 h,0,0,0'); // 获取处理后的项目数据
