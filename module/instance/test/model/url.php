#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::url();
timeout=0
cid=16827

- 步骤1：正常情况 @//example.com
- 步骤2：端口443不添加 @//test.example.com
- 步骤3：自定义端口 @//dev.example.com:8443/
- 步骤4：空域名 @//
- 步骤5：端口8080 @//staging.example.com:8080/

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 4. 测试步骤（必须包含至少5个测试步骤）

// 步骤1：测试正常域名情况，无端口环境变量
$instance1 = new stdClass();
$instance1->domain = 'example.com';
putenv('APP_HTTPS_PORT=');
r($instanceTest->urlTest($instance1)) && p() && e('//example.com'); // 步骤1：正常情况

// 步骤2：测试正常域名情况，端口为443
$instance2 = new stdClass();
$instance2->domain = 'test.example.com';
putenv('APP_HTTPS_PORT=443');
r($instanceTest->urlTest($instance2)) && p() && e('//test.example.com'); // 步骤2：端口443不添加

// 步骤3：测试正常域名情况，自定义端口8443
$instance3 = new stdClass();
$instance3->domain = 'dev.example.com';
putenv('APP_HTTPS_PORT=8443');
r($instanceTest->urlTest($instance3)) && p() && e('//dev.example.com:8443/'); // 步骤3：自定义端口

// 步骤4：测试空域名情况
$instance4 = new stdClass();
$instance4->domain = '';
putenv('APP_HTTPS_PORT=');
r($instanceTest->urlTest($instance4)) && p() && e('//'); // 步骤4：空域名

// 步骤5：测试域名包含端口情况，环境变量端口为8080
$instance5 = new stdClass();
$instance5->domain = 'staging.example.com';
putenv('APP_HTTPS_PORT=8080');
r($instanceTest->urlTest($instance5)) && p() && e('//staging.example.com:8080/'); // 步骤5：端口8080