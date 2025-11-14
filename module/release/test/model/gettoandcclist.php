#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::getToAndCcList();
timeout=0
cid=18003

- 测试有创建者和邮件通知人员的发布
 - 属性1 @user1
- 测试创建者为空但有邮件通知人员的发布
 - 属性1 @user2
- 测试产品PO为空的发布
 - 属性1 @user2
- 测试只有单个邮件通知人员且创建者为空的发布
 - 属性1 @po3
- 测试创建者和邮件通知人员都为空的发布
 - 属性1 @po1
- 测试有创建者但无邮件通知人员的发布
 - 属性1 @
- 测试关联产品不存在的发布
 - 属性1 @
- 测试邮件通知人员格式化处理
 - 属性1 @user2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

// 准备产品数据 - 必须先创建产品才能在release测试中引用
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->PO->range('po1{2},po2{1},{1},po3{1}'); // 包含空PO
$product->RD->range('dev1{1},{1},dev2{2},dev3{1}'); // 包含空RD
$product->gen(5);

// 准备用户数据
zenData('user')->gen(10);
su('admin');

$releaseTester = new releaseTest();

// 创建模拟发布数据对象进行测试
$release1 = new stdClass();
$release1->product = 1;
$release1->createdBy = 'admin';
$release1->mailto = 'user1,user2';

$release2 = new stdClass();
$release2->product = 2;
$release2->createdBy = '';
$release2->mailto = 'user1,user2';

$release3 = new stdClass();
$release3->product = 3;
$release3->createdBy = '';
$release3->mailto = 'user1,user2,user3';

$release4 = new stdClass();
$release4->product = 5;
$release4->createdBy = '';
$release4->mailto = 'user4';

$release5 = new stdClass();
$release5->product = 1;
$release5->createdBy = '';
$release5->mailto = '';

$release6 = new stdClass();
$release6->product = 5;
$release6->createdBy = 'test';
$release6->mailto = '';

$release7 = new stdClass();
$release7->product = 999; // 不存在的产品
$release7->createdBy = 'test';
$release7->mailto = '';

$release8 = new stdClass();
$release8->product = 1;
$release8->createdBy = '';
$release8->mailto = ' user1 , user2 '; // 测试邮件格式化

r($releaseTester->getToAndCcListTest($release1)) && p('0;1') && e('admin;user1,user2,po1,dev1'); // 测试有创建者和邮件通知人员的发布
r($releaseTester->getToAndCcListTest($release2)) && p('0;1') && e('user1;user2,po1,'); // 测试创建者为空但有邮件通知人员的发布
r($releaseTester->getToAndCcListTest($release3)) && p('0;1') && e('user1;user2,user3,po2,dev2'); // 测试产品PO为空的发布
r($releaseTester->getToAndCcListTest($release4)) && p('0;1') && e('user4;po3,dev3'); // 测试只有单个邮件通知人员且创建者为空的发布
r($releaseTester->getToAndCcListTest($release5)) && p('0;1') && e(';po1,dev1'); // 测试创建者和邮件通知人员都为空的发布
r($releaseTester->getToAndCcListTest($release6)) && p('0;1') && e('test;,po3,dev3'); // 测试有创建者但无邮件通知人员的发布
r($releaseTester->getToAndCcListTest($release7)) && p('0;1') && e('test;'); // 测试关联产品不存在的发布
r($releaseTester->getToAndCcListTest($release8)) && p('0;1') && e('user1;user2,po1,dev1'); // 测试邮件通知人员格式化处理