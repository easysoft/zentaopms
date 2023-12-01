#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

/**

title=测试 settingModel->setItems();
timeout=0
cid=1

- 创建空数据 @1
- 创建没有vision，只有视图且值为空的数据 @1
- 创建没有vision，只有所有者且值为空的数据 @1
- 创建没有vision，my模块且值为空的数据 @1
- 创建没有vision，只有所有者且值为空的数据 @1
- 创建没有vision，common模块且值为空的数据 @1
- 创建有vision，只有所有者且值为空的数据 @1
- 创建有vision，my模块且值为空的数据 @1
- 创建有vision，只有所有者且值为空的数据 @1
- 创建有vision，common模块且值为空的数据 @1
- 创建没有vision，只有视图且值project的数据 @1
- 创建没有vision，只有所有者且值为project的数据 @1
- 创建没有vision，my模块且值为project的数据 @1
- 创建没有vision，只有所有者且值为project的数据 @1
- 创建没有vision，common模块且值为project的数据 @1
- 创建有vision，只有所有者且值为project的数据 @1
- 创建有vision，my模块且值为project的数据 @1
- 创建有vision，只有所有者且值为project的数据 @1
- 创建有vision，common模块且值为project的数据 @1
- 创建没有vision，只有视图且值project和story的数据 @1
- 创建没有vision，只有所有者且值为project和story的数据 @1
- 创建没有vision，my模块且值为project和story的数据 @1
- 创建没有vision，只有所有者且值为project和story的数据 @1
- 创建没有vision，common模块且值为project和story的数据 @1
- 创建有vision，只有所有者且值为project和story的数据 @1
- 创建有vision，my模块且值为project和story的数据 @1
- 创建有vision，只有所有者且值为project和story的数据 @1
- 创建有vision，common模块且值为project和story的数据 @1

*/

$setting = new settingTest();

$pathList    = array();
$pathList[0] = "";
$pathList[1] = "@rnd";
$pathList[2] = "system";
$pathList[3] = "system.my";
$pathList[4] = "admin";
$pathList[5] = "admin.common";
$pathList[6] = "system@rnd";
$pathList[7] = "system.my@rnd";
$pathList[8] = "admin@rnd";
$pathList[9] = "admin.common@rnd";

$items = new stdclass();
r($setting->setItemsTest($pathList[0], $items)) && p() && e('1'); // 创建空数据
r($setting->setItemsTest($pathList[1], $items)) && p() && e('1'); // 创建没有vision，只有视图且值为空的数据
r($setting->setItemsTest($pathList[2], $items)) && p() && e('1'); // 创建没有vision，只有所有者且值为空的数据
r($setting->setItemsTest($pathList[3], $items)) && p() && e('1'); // 创建没有vision，my模块且值为空的数据
r($setting->setItemsTest($pathList[4], $items)) && p() && e('1'); // 创建没有vision，只有所有者且值为空的数据
r($setting->setItemsTest($pathList[5], $items)) && p() && e('1'); // 创建没有vision，common模块且值为空的数据
r($setting->setItemsTest($pathList[6], $items)) && p() && e('1'); // 创建有vision，只有所有者且值为空的数据
r($setting->setItemsTest($pathList[7], $items)) && p() && e('1'); // 创建有vision，my模块且值为空的数据
r($setting->setItemsTest($pathList[8], $items)) && p() && e('1'); // 创建有vision，只有所有者且值为空的数据
r($setting->setItemsTest($pathList[9], $items)) && p() && e('1'); // 创建有vision，common模块且值为空的数据

$items->projectList = 'project';
r($setting->setItemsTest($pathList[1], $items)) && p() && e('1'); // 创建没有vision，只有视图且值project的数据
r($setting->setItemsTest($pathList[2], $items)) && p() && e('1'); // 创建没有vision，只有所有者且值为project的数据
r($setting->setItemsTest($pathList[3], $items)) && p() && e('1'); // 创建没有vision，my模块且值为project的数据
r($setting->setItemsTest($pathList[4], $items)) && p() && e('1'); // 创建没有vision，只有所有者且值为project的数据
r($setting->setItemsTest($pathList[5], $items)) && p() && e('1'); // 创建没有vision，common模块且值为project的数据
r($setting->setItemsTest($pathList[6], $items)) && p() && e('1'); // 创建有vision，只有所有者且值为project的数据
r($setting->setItemsTest($pathList[7], $items)) && p() && e('1'); // 创建有vision，my模块且值为project的数据
r($setting->setItemsTest($pathList[8], $items)) && p() && e('1'); // 创建有vision，只有所有者且值为project的数据
r($setting->setItemsTest($pathList[9], $items)) && p() && e('1'); // 创建有vision，common模块且值为project的数据

$items->global = new stdclass();
$items->global->productList = 'story';
r($setting->setItemsTest($pathList[1], $items)) && p() && e('1'); // 创建没有vision，只有视图且值project和story的数据
r($setting->setItemsTest($pathList[2], $items)) && p() && e('1'); // 创建没有vision，只有所有者且值为project和story的数据
r($setting->setItemsTest($pathList[3], $items)) && p() && e('1'); // 创建没有vision，my模块且值为project和story的数据
r($setting->setItemsTest($pathList[4], $items)) && p() && e('1'); // 创建没有vision，只有所有者且值为project和story的数据
r($setting->setItemsTest($pathList[5], $items)) && p() && e('1'); // 创建没有vision，common模块且值为project和story的数据
r($setting->setItemsTest($pathList[6], $items)) && p() && e('1'); // 创建有vision，只有所有者且值为project和story的数据
r($setting->setItemsTest($pathList[7], $items)) && p() && e('1'); // 创建有vision，my模块且值为project和story的数据
r($setting->setItemsTest($pathList[8], $items)) && p() && e('1'); // 创建有vision，只有所有者且值为project和story的数据
r($setting->setItemsTest($pathList[9], $items)) && p() && e('1'); // 创建有vision，common模块且值为project和story的数据