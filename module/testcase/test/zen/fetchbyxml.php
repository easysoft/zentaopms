#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::fetchByXML();
timeout=0
cid=19089

- 步骤1：正常XML文件，没有产品ID
 - 属性result @success
 - 属性pID @1
 - 属性type @xml
- 步骤2：包含有效产品ID的XML标题
 - 属性result @success
 - 属性pID @2
 - 属性type @xml
- 步骤3：包含无效产品ID的XML标题属性result @fail
- 步骤4：空标题的XML文件属性result @fail
- 步骤5：文件不存在的情况属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$table->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$table->status->range('normal{8},closed{2}');
$table->deleted->range('0{9},1{1}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 准备测试数据目录
$testDataDir = dirname(__FILE__) . '/data/fetchbyxml';
if(!is_dir($testDataDir)) mkdir($testDataDir, 0777, true);

// 创建不同的测试XML文件
// 测试文件1：正常XML文件，没有产品ID
$xmlContent1 = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<xmap-content xmlns="urn:xmind:xmap:xmlns:content:2.0">
    <sheet id="sheet1">
        <topic id="topic1">
            <title>测试产品</title>
        </topic>
    </sheet>
</xmap-content>';

$testDir1 = $testDataDir . '/test1';
if(!is_dir($testDir1)) mkdir($testDir1, 0777, true);
file_put_contents($testDir1 . '/content.xml', $xmlContent1);

// 测试文件2：包含有效产品ID的XML标题
$xmlContent2 = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<xmap-content xmlns="urn:xmind:xmap:xmlns:content:2.0">
    <sheet id="sheet2">
        <topic id="topic2">
            <title>测试产品[2]</title>
        </topic>
    </sheet>
</xmap-content>';

$testDir2 = $testDataDir . '/test2';
if(!is_dir($testDir2)) mkdir($testDir2, 0777, true);
file_put_contents($testDir2 . '/content.xml', $xmlContent2);

// 测试文件3：包含无效产品ID的XML标题
$xmlContent3 = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<xmap-content xmlns="urn:xmind:xmap:xmlns:content:2.0">
    <sheet id="sheet3">
        <topic id="topic3">
            <title>测试产品[999]</title>
        </topic>
    </sheet>
</xmap-content>';

$testDir3 = $testDataDir . '/test3';
if(!is_dir($testDir3)) mkdir($testDir3, 0777, true);
file_put_contents($testDir3 . '/content.xml', $xmlContent3);

// 测试文件4：空标题的XML文件
$xmlContent4 = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<xmap-content xmlns="urn:xmind:xmap:xmlns:content:2.0">
    <sheet id="sheet4">
        <topic id="topic4">
            <title></title>
        </topic>
    </sheet>
</xmap-content>';

$testDir4 = $testDataDir . '/test4';
if(!is_dir($testDir4)) mkdir($testDir4, 0777, true);
file_put_contents($testDir4 . '/content.xml', $xmlContent4);

// 测试文件5：包含已删除产品ID的XML标题
$xmlContent5 = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<xmap-content xmlns="urn:xmind:xmap:xmlns:content:2.0">
    <sheet id="sheet5">
        <topic id="topic5">
            <title>测试产品[10]</title>
        </topic>
    </sheet>
</xmap-content>';

$testDir5 = $testDataDir . '/test5';
if(!is_dir($testDir5)) mkdir($testDir5, 0777, true);
file_put_contents($testDir5 . '/content.xml', $xmlContent5);

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->fetchByXMLTest($testDir1, 1)) && p('result,pID,type') && e('success,1,xml'); // 步骤1：正常XML文件，没有产品ID
r($testcaseTest->fetchByXMLTest($testDir2, 1)) && p('result,pID,type') && e('success,2,xml'); // 步骤2：包含有效产品ID的XML标题
r($testcaseTest->fetchByXMLTest($testDir3, 1)) && p('result') && e('fail'); // 步骤3：包含无效产品ID的XML标题
r($testcaseTest->fetchByXMLTest($testDir4, 1)) && p('result') && e('fail'); // 步骤4：空标题的XML文件
r($testcaseTest->fetchByXMLTest('/not/exist/path', 1)) && p('result') && e('fail'); // 步骤5：文件不存在的情况