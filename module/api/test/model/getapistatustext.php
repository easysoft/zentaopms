#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 apiModel->getApiStatusText();
timeout=0
cid=1

- 测试获取接口状态为doing对应的语言项。 @开发中
- 测试获取接口状态为done对应的语言项。 @开发完成
- 测试获取接口状态为wait对应的语言项。 @wait

*/

global $tester;
$tester->loadModel('api');

r($tester->api->getApiStatusText('doing')) && p() && e('开发中');   // 测试获取接口状态为doing对应的语言项。
r($tester->api->getApiStatusText('done'))  && p() && e('开发完成'); // 测试获取接口状态为done对应的语言项。
r($tester->api->getApiStatusText('wait'))  && p() && e('wait');     // 测试获取接口状态为wait对应的语言项。
