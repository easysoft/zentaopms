#!/usr/bin/env php
<?php

/**

title=测试 docZen::processOutline();
timeout=0
cid=0

- 执行$result1->content, "id='anchor0'") !== false @1
- 执行$result2->content, "id='anchor0'") === false @1
- 执行$result3->content == '<p>普通段落</p><div>没有标题的文档</div><span>其他内容</span> @1
- 执行$result4->content, "id='anchor") == 3 @1
- 执行content, "<span>带标签的</span>") !== false && strpos($result5模块的content, "id='anchor0'") !== false方法  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// processOutline方法不依赖数据库数据，直接使用对象测试

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试文档对象
$doc1 = new stdClass();
$doc1->id = 1;
$doc1->title = '测试文档1';
$doc1->content = '<h1>第一章</h1><p>内容1</p><h2>第一节</h2><p>内容2</p><h3>小节1</h3><p>内容3</p><h2>第二节</h2><p>内容4</p>';

$doc2 = new stdClass();
$doc2->id = 2;
$doc2->title = '测试文档2';
$doc2->content = '<h1></h1><p>内容1</p><h2>有效标题</h2><p>内容2</p><h3> </h3><p>内容3</p>';

$doc3 = new stdClass();
$doc3->id = 3;
$doc3->title = '测试文档3';
$doc3->content = '<p>普通段落</p><div>没有标题的文档</div><span>其他内容</span>';

$doc4 = new stdClass();
$doc4->id = 4;
$doc4->title = '测试文档4';
$doc4->content = '<h3>第三级标题</h3><p>内容1</p><h1>第一级标题</h1><p>内容2</p><h4>第四级标题</h4><p>内容3</p>';

$doc5 = new stdClass();
$doc5->id = 5;
$doc5->title = '测试文档5';
$doc5->content = '<h1><span>带标签的</span><strong>标题</strong></h1><p>内容1</p><h2>普通<em>标题</em></h2><p>内容2</p>';

// 步骤1：正常多级标题处理 - 检查是否包含锚点
$result1 = $docTest->processOutlineTest($doc1);
r(strpos($result1->content, "id='anchor0'") !== false) && p() && e('1');

// 步骤2：空标题处理 - 检查空标题不会获得锚点
$result2 = $docTest->processOutlineTest($doc2);
r(strpos($result2->content, "id='anchor0'") === false) && p() && e('1');

// 步骤3：无标题内容处理 - 检查原内容不变
$result3 = $docTest->processOutlineTest($doc3);
r($result3->content == '<p>普通段落</p><div>没有标题的文档</div><span>其他内容</span>') && p() && e('1');

// 步骤4：不规范层级处理 - 检查所有标题都获得锚点
$result4 = $docTest->processOutlineTest($doc4);
r(substr_count($result4->content, "id='anchor") == 3) && p() && e('1');

// 步骤5：HTML标签标题处理 - 检查含标签的标题正常处理
$result5 = $docTest->processOutlineTest($doc5);
r(strpos($result5->content, "<span>带标签的</span>") !== false && strpos($result5->content, "id='anchor0'") !== false) && p() && e('1');