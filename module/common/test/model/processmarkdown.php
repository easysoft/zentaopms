#!/usr/bin/env php
<?php

/**

title=测试 commonModel::processMarkdown();
timeout=0
cid=15703

- 执行commonTest模块的processMarkdownTest方法，参数是''  @0
- 执行commonTest模块的processMarkdownTest方法，参数是'Hello World'  @<p>Hello World</p>
- 执行commonTest模块的processMarkdownTest方法，参数是'# Title'  @<h1>Title</h1>
- 执行commonTest模块的processMarkdownTest方法，参数是'**bold**'  @<p><strong>bold</strong></p>
- 执行commonTest模块的processMarkdownTest方法，参数是'`code`'  @<p><code>code</code></p>
- 执行commonTest模块的processMarkdownTest方法，参数是'[link]  @<p><a href="http://test.com">link</a></p>
- 执行commonTest模块的processMarkdownTest方法，参数是'_italic_'  @<p><em>italic</em></p>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->processMarkdownTest('')) && p() && e('0');
r($commonTest->processMarkdownTest('Hello World')) && p() && e('<p>Hello World</p>');
r($commonTest->processMarkdownTest('# Title')) && p() && e('<h1>Title</h1>');
r($commonTest->processMarkdownTest('**bold**')) && p() && e('<p><strong>bold</strong></p>');
r($commonTest->processMarkdownTest('`code`')) && p() && e('<p><code>code</code></p>');
r($commonTest->processMarkdownTest('[link](http://test.com)')) && p() && e('<p><a href="http://test.com">link</a></p>');
r($commonTest->processMarkdownTest('_italic_')) && p() && e('<p><em>italic</em></p>');