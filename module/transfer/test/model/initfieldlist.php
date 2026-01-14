#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('product')->gen(10);
$module = zenData('module');
$module->root->range('1');
$module->type->range('story');
$module->gen(10);
su('admin');

/**

title=测试 transfer->initFieldList();
timeout=0
cid=19321

- 测试初始化导出需求时所属产品字段第product条的title属性 @所属产品
- 测试初始化导出需求时所属模块字段第module[items]条的2属性 @/这是一个模块2
- 测试初始化导出需求时来源备注字段第sourceNote条的title属性 @来源备注
- 测试初始化导出需求时来源字段
 - 第source条的title属性 @来源
 - 第source[items]条的customer属性 @客户

*/

$transfer = new transferModelTest();
$result   = $transfer->initFieldListTest('story');

r($result) && p('product:title')    && e('所属产品');       // 测试初始化导出需求时所属产品字段
r($result) && p('module[items]:2') && e('/这是一个模块2'); // 测试初始化导出需求时所属模块字段
r($result) && p('sourceNote:title') && e('来源备注');       // 测试初始化导出需求时来源备注字段
r($result) && p('source:title;source[items]:customer') && e('来源,客户');    // 测试初始化导出需求时来源字段
