#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $app, $tester;
$app->rawModule = 'provider';
$app->rawMethod = 'browse';
$app->loadClass('pager', true);

$tester->dao->delete()->from(TABLE_PROVIDER)->exec();

$providerRows = array(
    (object)array('id' => 1, 'type' => 'GitLab',  'name' => 'Alpha',   'url' => 'https://alpha.example.com',   'token' => 'alpha-token',   'deleted' => '0', 'createdBy' => 'admin', 'createdDate' => helper::now()),
    (object)array('id' => 2, 'type' => 'GitHub',  'name' => 'Bravo',   'url' => 'https://bravo.example.com',   'token' => 'bravo-token',   'deleted' => '0', 'createdBy' => 'admin', 'createdDate' => helper::now()),
    (object)array('id' => 3, 'type' => 'Gitea',   'name' => 'Charlie', 'url' => 'https://charlie.example.com', 'token' => 'charlie-token', 'deleted' => '0', 'createdBy' => 'admin', 'createdDate' => helper::now()),
    (object)array('id' => 4, 'type' => 'Gogs',    'name' => 'Delta',   'url' => 'https://delta.example.com',   'token' => 'delta-token',   'deleted' => '1', 'createdBy' => 'admin', 'createdDate' => helper::now()),
    (object)array('id' => 5, 'type' => 'Jenkins', 'name' => 'Echo',    'url' => 'https://echo.example.com',    'token' => 'echo-token',    'deleted' => '0', 'createdBy' => 'admin', 'createdDate' => helper::now())
);
foreach($providerRows as $providerRow) $tester->dao->insert(TABLE_PROVIDER)->data($providerRow)->exec();

/**

title=测试 providerModel::getList();
timeout=0
cid=0

- 步骤1：按 id 倒序获取服务列表时，第一条是最新未删除服务 @5
- 步骤2：按 id 正序获取服务列表时，第一条是最早未删除服务 @1
- 步骤3：按名称倒序获取服务列表时，第一条是 Echo @5
- 步骤4：分页获取第二页服务列表时，只返回当前页的两条未删除服务 @2,1
- 步骤5：服务列表会过滤已删除服务 @0
- 步骤6：服务列表总数只统计未删除服务 @4

*/

$providerTester = new providerModelTest();
$secondPagePager = new pager(4, 2, 2);

r(array_key_first($providerTester->getListTest('id_desc'))) && p() && e('5');                               // 步骤1：按 id 倒序获取服务列表时，第一条是最新未删除服务
r(array_key_first($providerTester->getListTest('id_asc'))) && p() && e('1');                                // 步骤2：按 id 正序获取服务列表时，第一条是最早未删除服务
r(array_key_first($providerTester->getListTest('name_desc'))) && p() && e('5');                             // 步骤3：按名称倒序获取服务列表时，第一条是 Echo
r(implode(',', array_keys($providerTester->getListTest('id_desc', $secondPagePager)))) && p() && e('2,1'); // 步骤4：分页获取第二页服务列表时，只返回当前页的两条未删除服务
r(isset($providerTester->getListTest('id_desc')[4])) && p() && e('0');                                      // 步骤5：服务列表会过滤已删除服务
r(count($providerTester->getListTest('id_desc'))) && p() && e('4');                                         // 步骤6：服务列表总数只统计未删除服务
