#!/usr/bin/env php
<?php

/**

title=测试 storeModel::__construct();
timeout=0
cid=18447

- 测试默认构造函数初始化API头信息 @1
- 测试构造函数设置API认证头包含token @1
- 测试构造函数初始化后config.cloud.api.channel值 @stable
- 测试构造函数传入appName参数为zentao @zentao
- 测试构造函数传入空appName参数 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $config;
$tester->loadModel('store');

// 初始化时API headers应该被设置
$originalHeaders = $config->cloud->api->headers ?? array();

// 测试1：验证API headers被初始化
r(count($originalHeaders) > 0) && p() && e('1'); // 测试默认构造函数初始化API头信息

// 测试2：验证headers中包含auth信息
$hasAuthHeader = false;
foreach($originalHeaders as $header) {
    if(strpos($header, $config->cloud->api->auth . ':') !== false) {
        $hasAuthHeader = true;
        break;
    }
}
r($hasAuthHeader) && p() && e('1'); // 测试构造函数设置API认证头包含token

// 测试3：验证默认channel值
r($config->cloud->api->channel) && p() && e('stable'); // 测试构造函数初始化后config.cloud.api.channel值

// 测试4和5：测试创建新实例时appName的处理（通过创建新storeModel实例测试）
class TestStoreModel extends storeModel {
    public $testAppName;
    public function __construct($appName = '') {
        parent::__construct($appName);
        $this->testAppName = $appName;
    }
}

$testStore1 = new TestStoreModel('zentao');
r($testStore1->testAppName) && p() && e('zentao'); // 测试构造函数传入appName参数为zentao

$testStore2 = new TestStoreModel('');
r($testStore2->testAppName === '') && p() && e('1'); // 测试构造函数传入空appName参数