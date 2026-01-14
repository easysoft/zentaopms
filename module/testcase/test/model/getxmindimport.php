#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::getXmindImport();
timeout=0
cid=19007

- 步骤1：正常XML文件 @{"root":{"node1":{"attr":"value1","text":"Content1"},"node2":"Content2","nested":{"child":"Child content"}}}

- 步骤2：空XML文件 @{"root":[]}
- 步骤3：不存在文件 @0
- 步骤4：无效XML文件 @0
- 步骤5：复杂XML文件 @{"root":{"test":{"attr":"value","text":"Test content"},"items":{"item":[{"id":"1","text":"Item 1"},{"id":"2","text":"Item 2"}]}}}

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseModelTest();

// 创建测试数据文件
$tempDir = sys_get_temp_dir() . '/zentao_test_xml';
if (!is_dir($tempDir)) mkdir($tempDir, 0777, true);

// 创建正常的XML文件
$validXmlFile = $tempDir . '/valid.xml';
file_put_contents($validXmlFile, '<?xml version="1.0" encoding="UTF-8"?>
<root>
    <node1 attr="value1">Content1</node1>
    <node2>Content2</node2>
    <nested>
        <child>Child content</child>
    </nested>
</root>');

// 创建空XML文件
$emptyXmlFile = $tempDir . '/empty.xml';
file_put_contents($emptyXmlFile, '<?xml version="1.0" encoding="UTF-8"?>
<root></root>');

// 创建无效XML文件
$invalidXmlFile = $tempDir . '/invalid.xml';
file_put_contents($invalidXmlFile, '<?xml version="1.0" encoding="UTF-8"?>
<root>
    <unclosed>content
</root>');

// 创建带命名空间的复杂XML文件
$complexXmlFile = $tempDir . '/complex.xml';
file_put_contents($complexXmlFile, '<?xml version="1.0" encoding="UTF-8"?>
<root xmlns:ns="http://example.com">
    <ns:test attr="value">Test content</ns:test>
    <items>
        <item id="1">Item 1</item>
        <item id="2">Item 2</item>
    </items>
</root>');

// 不存在的文件路径
$nonExistentFile = $tempDir . '/nonexistent.xml';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->getXmindImportTest($validXmlFile)) && p() && e('{"root":{"node1":{"attr":"value1","text":"Content1"},"node2":"Content2","nested":{"child":"Child content"}}}'); // 步骤1：正常XML文件
r($testcaseTest->getXmindImportTest($emptyXmlFile)) && p() && e('{"root":[]}'); // 步骤2：空XML文件
r($testcaseTest->getXmindImportTest($nonExistentFile)) && p() && e('0'); // 步骤3：不存在文件
r($testcaseTest->getXmindImportTest($invalidXmlFile)) && p() && e('0'); // 步骤4：无效XML文件
r($testcaseTest->getXmindImportTest($complexXmlFile)) && p() && e('{"root":{"test":{"attr":"value","text":"Test content"},"items":{"item":[{"id":"1","text":"Item 1"},{"id":"2","text":"Item 2"}]}}}'); // 步骤5：复杂XML文件

// 清理测试文件
unlink($validXmlFile);
unlink($emptyXmlFile);
unlink($invalidXmlFile);
unlink($complexXmlFile);
rmdir($tempDir);