#!/usr/bin/env php
<?php

/**

title=测试 docZen::buildOutlineList();
timeout=0
cid=0

- 执行docTest模块的buildOutlineListTest方法，参数是$topLevel, $content, $includeHeadElement 第0条的level属性 @1
- 执行$result2 @0
- 执行$result3 @0
- 执行docTest模块的buildOutlineListTest方法，参数是2, $mixedContent, array 第1条的parent属性 @0
- 执行$result5 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$docTest = new docTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：正常HTML内容构建大纲列表 - 验证第一个元素的level属性
$topLevel = 1;
$content = array(
    0 => '<h1>第一级标题1</h1>',
    1 => '<h2>第二级标题1</h2>',
    2 => '<p>段落内容</p>',
    3 => '<h2>第二级标题2</h2>',
    4 => '<h3>第三级标题1</h3>',
    5 => '<h1>第一级标题2</h1>'
);
$includeHeadElement = array('h1', 'h2', 'h3');
r($docTest->buildOutlineListTest($topLevel, $content, $includeHeadElement)) && p('0:level') && e('1');

// 步骤2：空内容数组测试 - 应该返回空数组
$result2 = $docTest->buildOutlineListTest(1, array(), array('h1', 'h2'));
r(count($result2)) && p() && e('0');

// 步骤3：无标题内容测试 - 应该返回空数组
$noHeadContent = array('<p>段落1</p>', '<div>内容</div>', '<span>文本</span>');
$result3 = $docTest->buildOutlineListTest(1, $noHeadContent, array('h1', 'h2'));
r(count($result3)) && p() && e('0');

// 步骤4：混合层级标题测试 - 测试父子关系建立
$mixedContent = array(
    0 => '<h2>二级标题A</h2>',
    1 => '<h3>三级标题A1</h3>',
    2 => '<h3>三级标题A2</h3>',
    3 => '<h2>二级标题B</h2>'
);
r($docTest->buildOutlineListTest(2, $mixedContent, array('h2', 'h3'))) && p('1:parent') && e('0');

// 步骤5：单一层级标题测试 - 验证结果数量正确
$singleLevelContent = array(
    0 => '<h1>标题一</h1>',
    1 => '<h1>标题二</h1>',
    2 => '<h1>标题三</h1>'
);
$result5 = $docTest->buildOutlineListTest(1, $singleLevelContent, array('h1'));
r(count($result5)) && p() && e('3');