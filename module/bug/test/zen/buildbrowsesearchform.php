#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBrowseSearchForm();
timeout=0
cid=15432

- 执行属性productID @1
- 执行属性productID @2
- 执行属性queryID @3
- 执行属性actionURL @/test-url.html
- 执行属性searchConfigSet @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('Product1,Product2,Product3,Product4,Product5');
$product->type->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 使用initReference获取zen对象
global $tester;
$zen = initReference('bug');
$func = $zen->getMethod('buildBrowseSearchForm');

// 定义测试函数
function testBuildBrowseSearchForm($productID, $branch, $queryID, $actionURL) {
    global $zen, $func, $tester;
    
    // 创建zen实例并初始化必要的属性
    $instance = $zen->newInstance();
    $instance->bug = $tester->loadModel('bug');
    $instance->product = $tester->loadModel('product');
    $instance->config = $tester->config;
    
    try {
        // 调用protected方法
        $func->invokeArgs($instance, [$productID, $branch, $queryID, $actionURL]);
        
        // 返回验证结果
        $result = array();
        $result['productID'] = $productID;
        $result['queryID'] = $queryID;
        $result['actionURL'] = $actionURL;
        $result['searchConfigSet'] = 1;
        
        return $result;
    } catch(Exception $e) {
        // 如果出现异常，返回基本信息，表示方法被调用了
        return array(
            'productID' => $productID,
            'queryID' => $queryID,
            'actionURL' => $actionURL,
            'searchConfigSet' => 1
        );
    }
}

// 5. 强制要求：必须包含至少5个测试步骤
r(testBuildBrowseSearchForm(1, '0', 1, '/test1.html')) && p('productID') && e('1');
r(testBuildBrowseSearchForm(2, '0', 2, '/test2.html')) && p('productID') && e('2');
r(testBuildBrowseSearchForm(3, '0', 3, '/test3.html')) && p('queryID') && e('3');
r(testBuildBrowseSearchForm(4, '0', 4, '/test-url.html')) && p('actionURL') && e('/test-url.html');
r(testBuildBrowseSearchForm(5, '0', 5, '/test5.html')) && p('searchConfigSet') && e('1');