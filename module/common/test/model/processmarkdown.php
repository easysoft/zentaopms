#!/usr/bin/env php
<?php

/**

title=测试 commonModel::processMarkdown();
timeout=0
cid=0

- 步骤1：基础标题转换 >> 期望返回h1标签
- 步骤2：复杂Markdown语法 >> 期望正确解析各种语法
- 步骤3：空字符串输入 >> 期望返回false
- 步骤4：纯文本输入 >> 期望包装为p标签
- 步骤5：特殊字符处理 >> 期望正确转义HTML字符

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->processMarkdownTest('# Hello World')) && p() && e('<h1>Hello World</h1>'); // 步骤1：基础标题转换
r($commonTest->processMarkdownTest("# Title\n\n**Bold** and *italic* text\n\n- List item 1\n- List item 2")) && p() && e("<h1>Title</h1>\n<p><strong>Bold</strong> and <em>italic</em> text</p>\n<ul>\n<li>List item 1</li>\n<li>List item 2</li>\n</ul>"); // 步骤2：复杂Markdown语法
r($commonTest->processMarkdownTest('')) && p() && e(false); // 步骤3：空字符串输入
r($commonTest->processMarkdownTest('Simple text without markdown')) && p() && e('<p>Simple text without markdown</p>'); // 步骤4：纯文本输入
r($commonTest->processMarkdownTest('Text with & < > special chars')) && p() && e('<p>Text with &amp; &lt; &gt; special chars</p>'); // 步骤5：特殊字符处理