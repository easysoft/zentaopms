#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::fetchByJSON();
cid=19088

- 测试正常JSON文件解析 >> 期望返回成功结果
- 测试带产品ID的JSON文件解析 >> 期望返回带产品ID的成功结果
- 测试空title的JSON文件 >> 期望返回失败结果
- 测试无效产品ID的JSON文件 >> 期望返回产品不存在错误
- 测试不存在的JSON文件 >> 期望返回文件不存在错误

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// zendata数据准备
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品{1-10}');
$product->deleted->range('0{9},1{1}');
$product->gen(10);

// 用户登录
su('admin');

// 创建测试实例
$testcaseZenTest = new testcaseZenTest();

// 创建测试目录和文件
$testDir = '/tmp/fetchbyjson_test';
if(!file_exists($testDir)) mkdir($testDir, 0755, true);

// 测试步骤1：正常JSON文件解析
$normalJsonData = array(
    array(
        'rootTopic' => array(
            'title' => '正常测试用例'
        )
    )
);
file_put_contents($testDir . '/content.json', json_encode($normalJsonData));
r($testcaseZenTest->fetchByJSONTest($testDir, 1, 'main')) && p('result,pID,type') && e('success,1,json');

// 测试步骤2：带产品ID的JSON文件解析
$productJsonData = array(
    array(
        'rootTopic' => array(
            'title' => '测试用例[2]'
        )
    )
);
file_put_contents($testDir . '/content.json', json_encode($productJsonData));
r($testcaseZenTest->fetchByJSONTest($testDir, 1, 'main')) && p('result,pID,type') && e('success,2,json');

// 测试步骤3：空title的JSON文件
$emptyTitleJsonData = array(
    array(
        'rootTopic' => array(
            'title' => ''
        )
    )
);
file_put_contents($testDir . '/content.json', json_encode($emptyTitleJsonData));
r($testcaseZenTest->fetchByJSONTest($testDir, 1, 'main')) && p('result') && e('fail');

// 测试步骤4：无效产品ID的JSON文件
$invalidProductJsonData = array(
    array(
        'rootTopic' => array(
            'title' => '测试用例[999]'
        )
    )
);
file_put_contents($testDir . '/content.json', json_encode($invalidProductJsonData));
r($testcaseZenTest->fetchByJSONTest($testDir, 1, 'main')) && p('result') && e('fail');

// 测试步骤5：不存在的JSON文件
if(file_exists($testDir . '/content.json')) unlink($testDir . '/content.json');
r($testcaseZenTest->fetchByJSONTest($testDir, 1, 'main')) && p('result') && e('fail');

// 清理测试目录
if(file_exists($testDir . '/content.json')) unlink($testDir . '/content.json');
if(file_exists($testDir)) rmdir($testDir);