#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 actionModel->formatActionComment();
timeout=0
cid=14886

- 测试纯文本处理 @Simple text
- 测试HTML标签保留 @<p>This is a <strong>formatted</strong> text.</p>
- 测试HTML代码转义 @Before&lt;div&gt;test&lt;/div&gt;After
- 测试空字符串处理 @0
- 测试另一个纯文本处理 @Line with newline

*/

$actionTest = new actionModelTest();

r($actionTest->formatActionCommentTest("Simple text")) && p() && e("Simple text"); // 测试纯文本处理
r($actionTest->formatActionCommentTest("<p>This is a <strong>formatted</strong> text.</p>")) && p() && e("<p>This is a <strong>formatted</strong> text.</p>"); // 测试HTML标签保留
r($actionTest->formatActionCommentTest('Before<pre class="prettyprint lang-html"><div>test</div></pre>After')) && p() && e('Before&lt;div&gt;test&lt;/div&gt;After'); // 测试HTML代码转义
r($actionTest->formatActionCommentTest("")) && p() && e("0"); // 测试空字符串处理
r($actionTest->formatActionCommentTest("Line with newline")) && p() && e("Line with newline"); // 测试另一个纯文本处理