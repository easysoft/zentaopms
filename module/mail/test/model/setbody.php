#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setBody();
timeout=0
cid=17018

- 步骤1：正常文本内容设置属性Body @Hello World
- 步骤2：HTML格式内容设置属性Body @<p>HTML content</p>
- 步骤3：空字符串设置属性Body @~~
- 步骤4：特殊字符内容设置属性Body @Special: &<>
- 步骤5：多行文本内容设置属性Body @Multi line text
- 步骤6：重复文本内容设置属性Body @Text. Text. Text. Text. Text. Text. Text. Text. Text. Text.
- 步骤7：HTML结构设置属性Body @<html><body><h1>Title</h1></body></html>

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$mailTest = new mailModelTest();

// 4. 强制要求：必须包含至少7个测试步骤
r($mailTest->setBodyTest('Hello World')) && p('Body') && e('Hello World'); // 步骤1：正常文本内容设置
r($mailTest->setBodyTest('<p>HTML content</p>')) && p('Body') && e('<p>HTML content</p>'); // 步骤2：HTML格式内容设置
r($mailTest->setBodyTest('')) && p('Body') && e('~~'); // 步骤3：空字符串设置
r($mailTest->setBodyTest('Special: &<>')) && p('Body') && e('Special: &<>'); // 步骤4：特殊字符内容设置
r($mailTest->setBodyTest('Multi line text')) && p('Body') && e('Multi line text'); // 步骤5：多行文本内容设置
r($mailTest->setBodyTest('Text. Text. Text. Text. Text. Text. Text. Text. Text. Text.')) && p('Body') && e('Text. Text. Text. Text. Text. Text. Text. Text. Text. Text.'); // 步骤6：重复文本内容设置
r($mailTest->setBodyTest('<html><body><h1>Title</h1></body></html>')) && p('Body') && e('<html><body><h1>Title</h1></body></html>'); // 步骤7：HTML结构设置