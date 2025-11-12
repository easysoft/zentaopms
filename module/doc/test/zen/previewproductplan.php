#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProductplan();
timeout=0
cid=0

- 步骤1:setting视图下预览产品1的所有计划 @3
- 步骤2:setting视图下预览产品2的所有计划 @2
- 步骤3:setting视图下预览不存在的产品计划 @0
- 步骤4:list视图下根据ID列表预览计划 @3
- 步骤5:list视图下使用空idList @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$productplanTable = zenData('productplan');
$productplanTable->product->range('1{3},2{2}');
$productplanTable->title->range('1-5')->prefix('计划');
$productplanTable->status->range('wait{2},doing{2},done{1}');
$productplanTable->begin->range('`2024-01-01`,`2024-02-01`,`2024-03-01`,`2024-04-01`,`2024-05-01`');
$productplanTable->end->range('`2024-06-01`,`2024-07-01`,`2024-08-01`,`2024-09-01`,`2024-10-01`');
$productplanTable->deleted->range('0');
$productplanTable->gen(5);

zenData('user')->gen(5);
zenData('product')->gen(2);

su('admin');

$docTest = new docZenTest();

$settingsProduct1 = array('action' => 'preview', 'product' => 1);
$settingsProduct2 = array('action' => 'preview', 'product' => 2);
$settingsNoProduct = array('action' => 'preview', 'product' => 999);
$settingsList = array('action' => 'list');
$idList = '1,2,3';

r(count($docTest->previewProductplanTest('setting', $settingsProduct1, '')['data'])) && p() && e('3'); // 步骤1:setting视图下预览产品1的所有计划
r(count($docTest->previewProductplanTest('setting', $settingsProduct2, '')['data'])) && p() && e('2'); // 步骤2:setting视图下预览产品2的所有计划
r(count($docTest->previewProductplanTest('setting', $settingsNoProduct, '')['data'])) && p() && e('0'); // 步骤3:setting视图下预览不存在的产品计划
r(count($docTest->previewProductplanTest('list', $settingsList, $idList)['data'])) && p() && e('3'); // 步骤4:list视图下根据ID列表预览计划
r(count($docTest->previewProductplanTest('list', $settingsList, '')['data'])) && p() && e('0'); // 步骤5:list视图下使用空idList