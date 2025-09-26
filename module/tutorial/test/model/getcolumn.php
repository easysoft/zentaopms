#!/usr/bin/env php
<?php

/**

title=- 测试获取看板列的颜色属性 >> 期望返回
timeout=0
cid=333

- 执行tutorial模块的getColumnTest方法 属性id @1
- 执行tutorial模块的getColumnTest方法 属性type @backlog
- 执行tutorial模块的getColumnTest方法 属性name @Backlog
- 执行tutorial模块的getColumnTest方法 属性color @#333
- 执行tutorial模块的getColumnTest方法 属性limit @-1
- 执行tutorial模块的getColumnTest方法 属性order @0
- 执行tutorial模块的getColumnTest方法 属性archived @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorial = new tutorialTest();

r($tutorial->getColumnTest()) && p('id') && e('1');
r($tutorial->getColumnTest()) && p('type') && e('backlog');
r($tutorial->getColumnTest()) && p('name') && e('Backlog');
r($tutorial->getColumnTest()) && p('color') && e('#333');
r($tutorial->getColumnTest()) && p('limit') && e('-1');
r($tutorial->getColumnTest()) && p('order') && e('0');
r($tutorial->getColumnTest()) && p('archived') && e('0');