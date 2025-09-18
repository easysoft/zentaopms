#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::prepareCreateSnapshotExtras();
cid=0

- 测试正常快照名称输入情况 >> 期望返回正确的快照对象
- 测试普通字符快照名称 >> 期望返回正确的快照对象
- 测试空名称输入情况 >> 期望返回空名称快照对象
- 测试特殊字符快照名称 >> 期望返回正确的快照对象
- 测试长名称快照输入 >> 期望返回正确的快照对象

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('host');
$table->loadYaml('host_preparecreatesnapshotextras', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$zanodeTest = new zanodeTest();

// 准备测试节点对象
$node1 = new stdClass();
$node1->id = 101;
$node1->name = 'test-node-01';
$node1->osName = 'Ubuntu 20.04';

$node2 = new stdClass();
$node2->id = 102;
$node2->name = 'test-node-02';
$node2->osName = 'CentOS 7';

// 准备POST数据
global $app;

// 设置正确的模块和方法名以便form::data()能找到配置
$app->setModuleName('zanode');
$app->setMethodName('createsnapshot');

// 创建clean post函数，用于重置post数据
function setPostData($name, $desc = '')
{
    global $app;
    $_POST = array();
    $app->post = new stdClass();
    if($name !== null) $app->post->name = (string)$name;
    if($desc !== null) $app->post->desc = (string)$desc;
    $_POST['name'] = $app->post->name ?? '';
    $_POST['desc'] = $app->post->desc ?? '';
}

// 测试步骤1：正常快照名称
setPostData('snapshot-test-01', 'Test snapshot description');
r($zanodeTest->prepareCreateSnapshotExtrasTest($node1)) && p('name,status,from') && e('snapshot-test-01,creating,snapshot');

// 测试步骤2：普通字符快照名称
setPostData('test-snapshot-02', 'Another test snapshot');
r($zanodeTest->prepareCreateSnapshotExtrasTest($node1)) && p('name,status,from') && e('test-snapshot-02,creating,snapshot');

// 测试步骤3：空名称快照
setPostData('', 'Empty name test');
r($zanodeTest->prepareCreateSnapshotExtrasTest($node2)) && p('name,host,osName') && e(',102,CentOS 7');

// 测试步骤4：特殊字符快照名称
setPostData('test-snapshot_2024@01', 'Special characters test');
r($zanodeTest->prepareCreateSnapshotExtrasTest($node1)) && p('name,host,memory,disk') && e('test-snapshot_2024@01,101,0,0');

// 测试步骤5：长名称快照
setPostData('very-long-snapshot-name-with-multiple-words-and-numbers-2024-test', 'Long description for testing purposes with multiple words and details');
r($zanodeTest->prepareCreateSnapshotExtrasTest($node2)) && p('name,desc,status,fileSize') && e('very-long-snapshot-name-with-multiple-words-and-numbers-2024-test,Long description for testing purposes with multiple words and details,creating,0');