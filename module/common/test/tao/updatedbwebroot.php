#!/usr/bin/env php
<?php

/**

title=测试 commonTao::updateDBWebRoot();
timeout=0
cid=15729

- 执行commonTest模块的updateDBWebRootTest方法，参数是$mockDbConfig1  @success
- 执行commonTest模块的updateDBWebRootTest方法，参数是$mockDbConfig2  @success
- 执行commonTest模块的updateDBWebRootTest方法，参数是$mockDbConfig3  @success
- 执行commonTest模块的updateDBWebRootTest方法，参数是$mockDbConfig4  @success
- 执行commonTest模块的updateDBWebRootTest方法，参数是$mockDbConfig5  @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

// 创建模拟数据库配置对象
$mockDbConfig1 = new stdClass();
$mockDbConfig1->common = array();

$mockDbConfig2 = new stdClass();
$mockDbConfig2->common = array();
$webRootConfig = new stdClass();
$webRootConfig->key = 'webRoot';
$webRootConfig->value = '/zentao/';
$mockDbConfig2->common[] = $webRootConfig;

$mockDbConfig3 = new stdClass();
$mockDbConfig3->common = array();
$webRootConfig2 = new stdClass();
$webRootConfig2->key = 'webRoot';
$webRootConfig2->value = '/';
$mockDbConfig3->common[] = $webRootConfig2;

$mockDbConfig4 = new stdClass();
$mockDbConfig4->common = array();
$webRootConfig3 = new stdClass();
$webRootConfig3->key = 'webRoot';
$webRootConfig3->value = '/different/';
$mockDbConfig4->common[] = $webRootConfig3;

$mockDbConfig5 = new stdClass();
$mockDbConfig5->common = array();
$otherConfig = new stdClass();
$otherConfig->key = 'other';
$otherConfig->value = 'value';
$mockDbConfig5->common[] = $otherConfig;

r($commonTest->updateDBWebRootTest($mockDbConfig1)) && p() && e('success');
r($commonTest->updateDBWebRootTest($mockDbConfig2)) && p() && e('success');
r($commonTest->updateDBWebRootTest($mockDbConfig3)) && p() && e('success');
r($commonTest->updateDBWebRootTest($mockDbConfig4)) && p() && e('success');
r($commonTest->updateDBWebRootTest($mockDbConfig5)) && p() && e('success');