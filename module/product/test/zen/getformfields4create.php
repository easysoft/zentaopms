#!/usr/bin/env php
<?php

/**

title=测试 productZen::getFormFields4Create();
timeout=0
cid=17585

- 测试不传任何参数时返回的字段数组是否包含 name 字段第name条的type属性 @string
- 测试不传任何参数时 program 字段的默认值是否为空字符串第program条的default属性 @~~
- 测试不传任何参数时 PO 字段的默认值是否为当前用户第PO条的default属性 @admin
- 测试传入 programID=1 时 program 字段的默认值是否为 1第program条的default属性 @1
- 测试传入 extra='name=测试产品' 时 name 字段的默认值第name条的default属性 @测试产品
- 测试传入 extra='PO=user1' 时 PO 字段的默认值第PO条的default属性 @user1
- 测试 name 字段是否为必填字段第name条的required属性 @1
- 测试 program 字段的控件类型是否为 select第program条的control属性 @select
- 测试 line 字段的控件类型是否为 select第line条的control属性 @select
- 测试 type 字段的默认值是否为 normal第type条的default属性 @normal
- 测试 status 字段的默认值是否为 normal第status条的default属性 @normal
- 测试 acl 字段的默认值是否为 open第acl条的default属性 @open

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->getFormFields4CreateTest(0, '')) && p('name:type') && e('string'); // 测试不传任何参数时返回的字段数组是否包含 name 字段
r($productTest->getFormFields4CreateTest(0, '')) && p('program:default') && e('~~'); // 测试不传任何参数时 program 字段的默认值是否为空字符串
r($productTest->getFormFields4CreateTest(0, '')) && p('PO:default') && e('admin'); // 测试不传任何参数时 PO 字段的默认值是否为当前用户
r($productTest->getFormFields4CreateTest(1, '')) && p('program:default') && e('1'); // 测试传入 programID=1 时 program 字段的默认值是否为 1
r($productTest->getFormFields4CreateTest(0, 'name=测试产品')) && p('name:default') && e('测试产品'); // 测试传入 extra='name=测试产品' 时 name 字段的默认值
r($productTest->getFormFields4CreateTest(0, 'PO=user1')) && p('PO:default') && e('user1'); // 测试传入 extra='PO=user1' 时 PO 字段的默认值
r($productTest->getFormFields4CreateTest(0, '')) && p('name:required') && e('1'); // 测试 name 字段是否为必填字段
r($productTest->getFormFields4CreateTest(0, '')) && p('program:control') && e('select'); // 测试 program 字段的控件类型是否为 select
r($productTest->getFormFields4CreateTest(0, '')) && p('line:control') && e('select'); // 测试 line 字段的控件类型是否为 select
r($productTest->getFormFields4CreateTest(0, '')) && p('type:default') && e('normal'); // 测试 type 字段的默认值是否为 normal
r($productTest->getFormFields4CreateTest(0, '')) && p('status:default') && e('normal'); // 测试 status 字段的默认值是否为 normal
r($productTest->getFormFields4CreateTest(0, '')) && p('acl:default') && e('open'); // 测试 acl 字段的默认值是否为 open