#!/usr/bin/env php
<?php

/**

title=测试 blockZen::initBlock();
timeout=0
cid=15244

- 执行blockTest模块的zenInitBlockTest方法，参数是'my'  @1
- 执行blockTest模块的zenInitBlockTest方法，参数是'qa'  @1
- 执行blockTest模块的zenInitBlockTest方法，参数是''  @0
- 执行blockTest模块的zenInitBlockTest方法，参数是'product'  @1
- 执行blockTest模块的zenInitBlockTest方法，参数是'my'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

$blockTest = new blockTest();

r($blockTest->zenInitBlockTest('my')) && p() && e(1);
r($blockTest->zenInitBlockTest('qa')) && p() && e(1);
r($blockTest->zenInitBlockTest('')) && p() && e(0);
r($blockTest->zenInitBlockTest('product')) && p() && e(1);
r($blockTest->zenInitBlockTest('my')) && p() && e(1);