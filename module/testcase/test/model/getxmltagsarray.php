#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::getXmlTagsArray();
timeout=0
cid=19008

- 步骤1：普通XML标签解析 @1
- 步骤2：包含命名空间的XML标签解析 @1
- 步骤3：多个相同标签名的XML解析 @1
- 步骤4：复杂嵌套XML标签解析 @1
- 步骤5：空XML或无子标签情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->getXmlTagsArrayTest('<root><item>test1</item><item>test2</item></root>', array('' => null), array('removeNamespace' => true, 'autoArray' => true, 'alwaysArray' => array(), 'keySearch' => false, 'keyReplace' => false, 'namespaceSeparator' => ':'))) && p() && e('1'); // 步骤1：普通XML标签解析
r($testcaseTest->getXmlTagsArrayTest('<root xmlns:ns="http://example.com"><ns:item>test</ns:item></root>', array('ns' => 'http://example.com', '' => null), array('removeNamespace' => false, 'namespaceSeparator' => ':', 'alwaysArray' => array(), 'autoArray' => true, 'keySearch' => false, 'keyReplace' => false))) && p() && e('1'); // 步骤2：包含命名空间的XML标签解析
r($testcaseTest->getXmlTagsArrayTest('<root><item>first</item><item>second</item><item>third</item></root>', array('' => null), array('removeNamespace' => true, 'autoArray' => true, 'alwaysArray' => array(), 'keySearch' => false, 'keyReplace' => false, 'namespaceSeparator' => ':'))) && p() && e('1'); // 步骤3：多个相同标签名的XML解析
r($testcaseTest->getXmlTagsArrayTest('<root><parent><child>nested</child></parent></root>', array('' => null), array('removeNamespace' => true, 'autoArray' => true, 'alwaysArray' => array(), 'keySearch' => false, 'keyReplace' => false, 'namespaceSeparator' => ':'))) && p() && e('1'); // 步骤4：复杂嵌套XML标签解析
r($testcaseTest->getXmlTagsArrayTest('<root></root>', array('' => null), array('removeNamespace' => true, 'autoArray' => true, 'alwaysArray' => array(), 'keySearch' => false, 'keyReplace' => false, 'namespaceSeparator' => ':'))) && p() && e('0'); // 步骤5：空XML或无子标签情况