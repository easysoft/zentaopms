#!/usr/bin/env php
<?php

/**

title=测试 productZen::getFormFields4Close();
timeout=0
cid=17584

- 测试返回的字段数组是否包含 status 字段第status条的type属性 @string
- 测试返回的字段数组是否包含 closedDate 字段第closedDate条的type属性 @string
- 测试返回的字段数组是否包含 comment 字段第comment条的type属性 @string
- 测试 status 字段的默认值是否为 close第status条的default属性 @close
- 测试 comment 字段的控件类型是否为 editor第comment条的control属性 @editor
- 测试 status 字段的控件类型是否为 hidden第status条的control属性 @hidden
- 测试 closedDate 字段的控件类型是否为 hidden第closedDate条的control属性 @hidden
- 测试 comment 字段的 width 属性是否为 full第comment条的width属性 @full

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$productTest = new productZenTest();

r($productTest->getFormFields4CloseTest()) && p('status:type')         && e('string');  // 测试返回的字段数组是否包含 status 字段
r($productTest->getFormFields4CloseTest()) && p('closedDate:type')     && e('string');  // 测试返回的字段数组是否包含 closedDate 字段
r($productTest->getFormFields4CloseTest()) && p('comment:type')        && e('string');  // 测试返回的字段数组是否包含 comment 字段
r($productTest->getFormFields4CloseTest()) && p('status:default')      && e('close');   // 测试 status 字段的默认值是否为 close
r($productTest->getFormFields4CloseTest()) && p('comment:control')     && e('editor');  // 测试 comment 字段的控件类型是否为 editor
r($productTest->getFormFields4CloseTest()) && p('status:control')      && e('hidden');  // 测试 status 字段的控件类型是否为 hidden
r($productTest->getFormFields4CloseTest()) && p('closedDate:control')  && e('hidden');  // 测试 closedDate 字段的控件类型是否为 hidden
r($productTest->getFormFields4CloseTest()) && p('comment:width')       && e('full');    // 测试 comment 字段的 width 属性是否为 full