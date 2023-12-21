#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('product')->gen(10);
$module = zdTable('module');
$module->root->range('1');
$module->type->range('story');
$module->gen(10);
su('admin');

/**

title=测试 transfer->initFieldListTest();
timeout=0
cid=1

- 测试初始化导出需求时所属产品字段第product条的title属性 @所属产品
- 测试初始化导出需求时所属模块字段第module[values]条的2属性 @/这是一个模块2
- 测试初始化导出需求时来源备注字段第sourceNote条的title属性 @来源备注
- 测试初始化导出需求时来源字段
 - 第source条的title属性 @来源
 - 第source[values]条的customer属性 @客户

*/

$transfer = new transferTest();
$result   = $transfer->initFieldListTest('story');

r($result) && p('product:title')    && e('所属产品');       // 测试初始化导出需求时所属产品字段
r($result) && p('module[values]:2') && e('/这是一个模块2'); // 测试初始化导出需求时所属模块字段
r($result) && p('sourceNote:title') && e('来源备注');       // 测试初始化导出需求时来源备注字段
r($result) && p('source:title;source[values]:customer') && e('来源,客户');    // 测试初始化导出需求时来源字段
