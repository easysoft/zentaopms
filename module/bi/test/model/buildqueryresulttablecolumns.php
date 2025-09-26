#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::buildQueryResultTableColumns();
timeout=0
cid=0

- 执行biTest模块的buildQueryResultTableColumnsTest方法，参数是array  @0
- 执行biTest模块的buildQueryResultTableColumnsTest方法，参数是$testData1 第0条的name属性 @id
- 执行biTest模块的buildQueryResultTableColumnsTest方法，参数是$testData1 第0条的title属性 @编号
- 执行biTest模块的buildQueryResultTableColumnsTest方法，参数是$testData2 第0条的title属性 @User ID
- 执行biTest模块的buildQueryResultTableColumnsTest方法，参数是$testData3 第0条的title属性 @code

*/

su('admin');
$biTest = new biTest();

// 测试步骤1：空字段设置数组边界测试
r(count($biTest->buildQueryResultTableColumnsTest(array()))) && p() && e('0');

// 测试步骤2：单个字段构建名称测试
$testData1 = array('id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int'));
r($biTest->buildQueryResultTableColumnsTest($testData1)) && p('0:name') && e('id');

// 测试步骤3：单个字段构建标题测试
r($biTest->buildQueryResultTableColumnsTest($testData1)) && p('0:title') && e('编号');

// 测试步骤4：多语言环境字段标题获取测试
global $app;
$originalLang = $app->getClientLang();
$app->setClientLang('en');
$testData2 = array('user_id' => array('zh-cn' => '用户编号', 'en' => 'User ID', 'type' => 'int'));
r($biTest->buildQueryResultTableColumnsTest($testData2)) && p('0:title') && e('User ID');
$app->setClientLang($originalLang);

// 测试步骤5：缺少语言标识字段回退测试
$testData3 = array('code' => array('type' => 'string'));
r($biTest->buildQueryResultTableColumnsTest($testData3)) && p('0:title') && e('code');