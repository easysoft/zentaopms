#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::xmlToArray();
timeout=0
cid=19028

- 步骤1：简单XML转换 @{"root":{"item":"test"}}
- 步骤2：命名空间XML转换 @{"root":{"item":"test"}}
- 步骤3：带属性XML转换 @{"root":{"id":"123","item":"test"}}

- 步骤4：文本内容XML转换 @{"root":"Simple text content"}
- 步骤5：嵌套XML转换 @{"root":{"parent":{"child":"nested"}}}

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->xmlToArrayTest('<root><item>test</item></root>')) && p() && e('{"root":{"item":"test"}}'); // 步骤1：简单XML转换
r($testcaseTest->xmlToArrayTest('<root xmlns:ns="http://example.com"><ns:item>test</ns:item></root>')) && p() && e('{"root":{"item":"test"}}'); // 步骤2：命名空间XML转换
r($testcaseTest->xmlToArrayTest('<root id="123"><item>test</item></root>')) && p() && e('{"root":{"id":"123","item":"test"}}'); // 步骤3：带属性XML转换
r($testcaseTest->xmlToArrayTest('<root>Simple text content</root>')) && p() && e('{"root":"Simple text content"}'); // 步骤4：文本内容XML转换
r($testcaseTest->xmlToArrayTest('<root><parent><child>nested</child></parent></root>')) && p() && e('{"root":{"parent":{"child":"nested"}}}'); // 步骤5：嵌套XML转换