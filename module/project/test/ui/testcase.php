#!/usr/bin/env php
<?php

/**

title=项目下用例列表操作检查
timeout=0
cid=1

- 执行tester模块的checkTab方法，参数是'allTab', '4'
 - 最终测试状态 @SUCCESS
 - 测试结果 @allTab下显示用例数正确
- 执行tester模块的checkTab方法，参数是'waitingTab', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @waitingTab下显示用例数正确
- 执行tester模块的checkTab方法，参数是'storyChangedTab', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @storyChangedTab下显示用例数正确
- 执行tester模块的checkTab方法，参数是'storyNoCaseTab', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @storyNoCaseTab下显示用例数正确
- 执行tester模块的switchProduct方法，参数是'firstProduct', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @切换firstProduct查看用例数据成功
- 执行tester模块的switchProduct方法，参数是'secondProduct', '2'
 - 最终测试状态 @SUCCESS
 - 测试结果 @切换secondProduct查看用例数据成功

 */

chdir(__DIR__);
include '../lib/testcase.ui.class.php';

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);
