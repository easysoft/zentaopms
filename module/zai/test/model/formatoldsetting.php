#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::formatOldSetting();
timeout=0
cid=19770

- 测试空设置输入 @0
- 测试包含旧apiBaseUrl的设置格式化 @testhost.com,8080,oldtoken123
- 测试包含旧appToken的设置格式化 @existinghost.com,9090,tokenvalue
- 测试包含https协议的apiBaseUrl @securehost.com,443
- 测试同时包含旧格式字段的完整设置 @complexhost.com,8888,complextoken456,shouldremain

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试空设置 */
r($zai->formatOldSettingTest(null)) && p() && e('0'); // 测试空设置输入

/* 测试包含旧apiBaseUrl的设置 */
$oldSetting1 = new stdClass();
$oldSetting1->apiBaseUrl = 'http://testhost.com:8080/v1';
$oldSetting1->appToken = 'oldtoken123';
$result1 = $zai->formatOldSettingTest($oldSetting1);
r($result1) && p('host,port,token') && e('testhost.com,8080,oldtoken123'); // 测试包含旧apiBaseUrl的设置格式化

/* 测试仅包含appToken的设置 */
$oldSetting2 = new stdClass();
$oldSetting2->host = 'existinghost.com';
$oldSetting2->port = 9090;
$oldSetting2->appToken = 'tokenvalue';
$result2 = $zai->formatOldSettingTest($oldSetting2);
r($result2) && p('host,port,token') && e('existinghost.com,9090,tokenvalue'); // 测试包含旧appToken的设置格式化

/* 测试https协议的apiBaseUrl */
$oldSetting3 = new stdClass();
$oldSetting3->apiBaseUrl = 'https://securehost.com:443/v1';
$result3 = $zai->formatOldSettingTest($oldSetting3);
r($result3) && p('host,port') && e('securehost.com,443'); // 测试包含https协议的apiBaseUrl

/* 测试复杂的旧格式 */
$oldSetting4 = new stdClass();
$oldSetting4->apiBaseUrl = '///http://complexhost.com:8888/v1';
$oldSetting4->appToken = 'complextoken456';
$oldSetting4->existingField = 'shouldremain';
$result4 = $zai->formatOldSettingTest($oldSetting4);
r($result4) && p('host,port,token,existingField') && e('complexhost.com,8888,complextoken456,shouldremain'); // 测试同时包含旧格式字段的完整设置
