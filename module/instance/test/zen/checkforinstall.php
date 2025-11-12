#!/usr/bin/env php
<?php

/**

title=测试 instanceZen::checkForInstall();
timeout=0
cid=0

- 步骤1:正常情况 @0
- 步骤2:域名为空属性message @域名长度必须介于2-20字符之间
- 步骤3:域名在保留列表属性message @console域名已被占用，请使用其它域名。
- 步骤4:域名包含大写属性message @域名只能是小写英文字母和数字
- 步骤5:域名包含特殊字符属性message @域名只能是小写英文字母和数字
- 步骤6:域名长度过短属性message @域名长度必须介于2-20字符之间
- 步骤7:域名长度过长属性message @域名长度必须介于2-20字符之间
- 步骤8:应用名称为空第message条的customName属性 @『服务名称』不能为空。
- 步骤9:应用名称已存在第message条的customName属性 @『服务名称』已经有『存在的实例名称』这条记录了。
- 步骤10:s3保留域名属性message @s3域名已被占用，请使用其它域名。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$instance = zenData('instance');
$instance->id->range('1-5');
$instance->name->range('存在的实例名称,Instance2,Instance3,Instance4,Instance5');
$instance->domain->range('existing.test.com,domain2.test.com,domain3.test.com,domain4.test.com,domain5.test.com');
$instance->deleted->range('0');
$instance->gen(5);

$instanceTest = new instanceZenTest();

$customData1 = new stdclass();
$customData1->customName = '新应用名称';
$customData1->customDomain = 'newdomain';
r($instanceTest->checkForInstallTest($customData1)) && p() && e('0'); // 步骤1:正常情况

$customData2 = new stdclass();
$customData2->customName = '新应用名称2';
$customData2->customDomain = '';
r($instanceTest->checkForInstallTest($customData2)) && p('message') && e('域名长度必须介于2-20字符之间'); // 步骤2:域名为空

$customData3 = new stdclass();
$customData3->customName = '新应用名称3';
$customData3->customDomain = 'console';
r($instanceTest->checkForInstallTest($customData3)) && p('message') && e('console域名已被占用，请使用其它域名。'); // 步骤3:域名在保留列表

$customData4 = new stdclass();
$customData4->customName = '新应用名称4';
$customData4->customDomain = 'TestDomain';
r($instanceTest->checkForInstallTest($customData4)) && p('message') && e('域名只能是小写英文字母和数字'); // 步骤4:域名包含大写

$customData5 = new stdclass();
$customData5->customName = '新应用名称5';
$customData5->customDomain = 'test-domain';
r($instanceTest->checkForInstallTest($customData5)) && p('message') && e('域名只能是小写英文字母和数字'); // 步骤5:域名包含特殊字符

$customData6 = new stdclass();
$customData6->customName = '新应用名称6';
$customData6->customDomain = 'a';
r($instanceTest->checkForInstallTest($customData6)) && p('message') && e('域名长度必须介于2-20字符之间'); // 步骤6:域名长度过短

$customData7 = new stdclass();
$customData7->customName = '新应用名称7';
$customData7->customDomain = 'verylongdomainname123';
r($instanceTest->checkForInstallTest($customData7)) && p('message') && e('域名长度必须介于2-20字符之间'); // 步骤7:域名长度过长

$customData8 = new stdclass();
$customData8->customName = '';
$customData8->customDomain = 'valid123';
r($instanceTest->checkForInstallTest($customData8)) && p('message:customName') && e('『服务名称』不能为空。'); // 步骤8:应用名称为空

$customData9 = new stdclass();
$customData9->customName = '存在的实例名称';
$customData9->customDomain = 'uniquedomain';
r($instanceTest->checkForInstallTest($customData9)) && p('message:customName') && e('『服务名称』已经有『存在的实例名称』这条记录了。'); // 步骤9:应用名称已存在

$customData10 = new stdclass();
$customData10->customName = '新应用名称10';
$customData10->customDomain = 's3';
r($instanceTest->checkForInstallTest($customData10)) && p('message') && e('s3域名已被占用，请使用其它域名。'); // 步骤10:s3保留域名