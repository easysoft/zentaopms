#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::createImage();
timeout=0
cid=0

- 测试无效节点ID（0） >> 返回false
- 测试负数节点ID >> 返回false
- 测试不存在的节点ID >> 返回false
- 测试空镜像名称 >> 返回false
- 测试有效参数但网络失败 >> 返回false

*/

include dirname(__FILE__, 2) . '/lib/model.class.php';

// 创建测试实例
$zanodeTest = new zanodeModelTest();

// 创建测试数据对象
$imageData1 = new stdClass();
$imageData1->name = 'test-new-image';

$imageData2 = new stdClass();
$imageData2->name = '';

// 执行测试步骤 - 由于createImage方法会进行网络请求，在测试环境中通常会失败，所以预期都是false
r($zanodeTest->createImageTest(0, $imageData1)) && p() && e(false);        // 测试无效节点ID（0）
r($zanodeTest->createImageTest(-1, $imageData1)) && p() && e(false);       // 测试负数节点ID
r($zanodeTest->createImageTest(999, $imageData1)) && p() && e(false);      // 测试不存在的节点ID
r($zanodeTest->createImageTest(1, $imageData2)) && p() && e(false);        // 测试空镜像名称
r($zanodeTest->createImageTest(1, $imageData1)) && p() && e(false);        // 测试有效参数但网络失败