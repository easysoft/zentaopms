#!/usr/bin/env php
<?php

/**

title=测试 editorZen::buildContentByAction();
timeout=0
cid=16244

- 执行editorTest模块的buildContentByActionTest方法，参数是'', 'edit', ''  @0
- 执行editorTest模块的buildContentByActionTest方法，参数是'/tmp/test_php_file.php', 'edit', ''  @<?php echo 'test';
- 执行editorTest模块的buildContentByActionTest方法，参数是'/tmp/test_php_file.php', 'override', ''  @~~
- 执行editorTest模块的buildContentByActionTest方法，参数是'/tmp/newfile.php', 'unknown', ''  @<?php echo 'test';
- 执行editorTest模块的buildContentByActionTest方法，参数是'/tmp/newfile.txt', 'unknown', ''  @~~
- 执行editorTest模块的buildContentByActionTest方法，参数是'', 'extendModel', ''  @<?php
- 执行editorTest模块的buildContentByActionTest方法，参数是'/tmp/nonexistent.txt', 'edit', ''  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editorzen.unittest.class.php';

su('admin');

$editorTest = new editorZenTest();

r($editorTest->buildContentByActionTest('', 'edit', '')) && p() && e('0');
r($editorTest->buildContentByActionTest('/tmp/test_php_file.php', 'edit', '')) && p() && e("<?php echo 'test';");
r($editorTest->buildContentByActionTest('/tmp/test_php_file.php', 'override', '')) && p() && e('~~');
r($editorTest->buildContentByActionTest('/tmp/newfile.php', 'unknown', '')) && p() && e("<?php echo 'test';");
r($editorTest->buildContentByActionTest('/tmp/newfile.txt', 'unknown', '')) && p() && e('~~');
r($editorTest->buildContentByActionTest('', 'extendModel', '')) && p() && e("<?php");
r($editorTest->buildContentByActionTest('/tmp/nonexistent.txt', 'edit', '')) && p() && e('~~');