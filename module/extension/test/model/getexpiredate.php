#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::getExpireDate();
timeout=0
cid=16458

- 步骤1：无授权文件情况 @0
- 步骤2：终生版授权 @life
- 步骤3：试用版授权（31天） @2024-02-01
- 步骤4：年版授权（365天） @2024-12-31
- 步骤5：自定义天数授权（180天） @2024-06-29

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 创建测试实例
$extensionTest = new extensionModelTest();

// 获取配置目录，准备授权文件测试数据
global $app;
$licencePath = $app->getConfigRoot() . 'license/';
if(!is_dir($licencePath)) mkdir($licencePath, 0755, true);

// 准备测试插件对象
$extension1 = new stdclass();
$extension1->code    = 'testcode1';
$extension1->version = 'v1.0';

$extension2 = new stdclass();
$extension2->code    = 'lifecode';
$extension2->version = 'v2.0';

$extension3 = new stdclass();
$extension3->code    = 'democode';
$extension3->version = 'v1.5';

$extension4 = new stdclass();
$extension4->code    = 'yearcode';
$extension4->version = 'v3.0';

$extension5 = new stdclass();
$extension5->code    = 'customcode';
$extension5->version = 'v1.2';

// 准备授权文件数据
$baseDate = '2024-01-01';

// 终生版授权数据
$lifeOrder = new stdclass();
$lifeOrder->type = 'life';
$lifeOrder->paidDate = $baseDate;
$lifeOrder->createdDate = $baseDate;
file_put_contents($licencePath . 'order_lifecodev2.0.txt', serialize($lifeOrder));

// 试用版授权数据
$demoOrder = new stdclass();
$demoOrder->type = 'demo';
$demoOrder->paidDate = $baseDate;
$demoOrder->createdDate = $baseDate;
file_put_contents($licencePath . 'order_democodev1.5.txt', serialize($demoOrder));

// 年版授权数据
$yearOrder = new stdclass();
$yearOrder->type = 'year';
$yearOrder->paidDate = $baseDate;
$yearOrder->createdDate = $baseDate;
file_put_contents($licencePath . 'order_yearcodev3.0.txt', serialize($yearOrder));

// 自定义天数授权数据
$customOrder = new stdclass();
$customOrder->type = 'custom';
$customOrder->days = 180;
$customOrder->paidDate = $baseDate;
$customOrder->createdDate = $baseDate;
file_put_contents($licencePath . 'order_customcodev1.2.txt', serialize($customOrder));

// 用户登录
su('admin');

// 执行测试
r($extensionTest->getExpireDateTest($extension1)) && p() && e('0');                  // 步骤1：无授权文件情况
r($extensionTest->getExpireDateTest($extension2)) && p() && e('life');               // 步骤2：终生版授权
r($extensionTest->getExpireDateTest($extension3)) && p() && e('2024-02-01');         // 步骤3：试用版授权（31天）
r($extensionTest->getExpireDateTest($extension4)) && p() && e('2024-12-31');         // 步骤4：年版授权（365天）
r($extensionTest->getExpireDateTest($extension5)) && p() && e('2024-06-29');         // 步骤5：自定义天数授权（180天）