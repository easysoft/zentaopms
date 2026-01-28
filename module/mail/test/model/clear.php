#!/usr/bin/env php
<?php

/**

title=测试 mailModel::clear();
timeout=0
cid=17004

- 执行mailTest模块的clearTest方法 属性processed @1
- 执行mailTest模块的clearTest方法 属性cleared @1
- 执行mailTest模块的clearTest方法 属性processed @1
- 执行mailTest模块的clearTest方法 属性cleared @1
- 执行mailTest模块的clearTest方法
 - 属性processed @1
 - 属性cleared @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mailTest = new mailModelTest();

r($mailTest->clearTest()) && p('processed') && e('1');
r($mailTest->clearTest()) && p('cleared') && e('1');
r($mailTest->clearTest()) && p('processed') && e('1');
r($mailTest->clearTest()) && p('cleared') && e('1');
r($mailTest->clearTest()) && p('processed,cleared') && e('1,1');