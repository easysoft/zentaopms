#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/ajaxgetdropmenu.ui.class.php';

/**

title=Ajax获取project下拉菜单测试
timeout=0
cid=1

- Ajaxgetdropmenu在Bug创建页面测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @Ajaxgetdropmenu在Bug创建页面测试成功
- Ajaxgetdropmenu在Bug编辑页面测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @Ajaxgetdropmenu在Bug编辑页面测试成功

*/

$product = zenData('product');
$product->id->range('1,2,3');
$product->name->range('产品1,产品2,产品3');
$product->gen(3);

$products = array(
    array('productID' => 1, 'name' => '产品1'),
    array('productID' => 2, 'name' => '产品2'),
    array('productID' => 3, 'name' => '产品3'),
);

/*
$bug = zenData('bug');
$bug->product->range('1');
$bug->openedBuild->range('trunk');
$bug->gen(3);
*/

global $lang;
$bug = array('title' => 'bug' . time(), 'openedBuild' => array('multiPicker' => '主干'));

$tester = new ajaxGetDropmenuTester();

r($tester->ajaxGetDropmenuInBugCreate($products, $bug)) && p('status,message') && e('SUCCESS,Ajaxgetdropmenu在Bug创建页面测试成功'); //Ajaxgetdropmenu在Bug创建页面测试
r($tester->ajaxGetDropmenuInBugEdit($products, $bug))   && p('status,message') && e('SUCCESS,Ajaxgetdropmenu在Bug编辑页面测试成功'); //Ajaxgetdropmenu在Bug编辑页面测试

$tester->closeBrowser();