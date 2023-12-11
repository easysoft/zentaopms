#!/usr/bin/env php
<?php
/**

title=测试 extensionModel->getExpiringPlugins();
timeout=0
cid=1

- 获取即将到期的插件列表。 @0
- 按照分组获取即将到期的插件列表。 @0
- 按照分组获取已到期的插件列表。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('extension');

r($tester->extension->getExpiringPlugins())     && p() && e(0); // 获取即将到期的插件列表。

$result = $tester->extension->getExpiringPlugins(true);
r($result['expiring']) && p() && e(0);                          // 按照分组获取即将到期的插件列表。
r($result['expired'])  && p() && e(0);                          // 按照分组获取已到期的插件列表。
