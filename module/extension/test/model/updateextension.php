#!/usr/bin/env php
<?php
/**

title=测试 extensionModel->updateExtension();
timeout=0
cid=1

- 测试数据为空时更新插件返回的结果。 @0
- 测试代号为空时更新插件返回的结果。 @0
- 测试更新代号为code1的插件状态为installed。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('extension')->gen(10);

global $tester;
$tester->loadModel('extension');

r($tester->extension->updateExtension(array())) && p() && e(0);                                           // 测试数据为空时更新插件返回的结果。
r($tester->extension->updateExtension(array('code' => '', 'status' => 'installed'))) && p() && e(0);      // 测试代号为空时更新插件返回的结果。
r($tester->extension->updateExtension(array('code' => 'code1', 'status' => 'installed'))) && p() && e(1); // 测试更新代号为code1的插件状态为installed。
