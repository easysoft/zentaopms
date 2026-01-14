#!/usr/bin/env php
<?php

/**

title=测试 blockModel::getBlockInitStatus();
timeout=0
cid=15228

- 执行blockTest模块的getBlockInitStatusTest方法，参数是'my'  @1
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'product'  @0
- 执行blockTest模块的getBlockInitStatusTest方法，参数是''  @0
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'nonexistent_dashboard'  @0
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'project'  @0
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'my'  @1
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'my'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$tester->loadModel('setting')->setItem("admin.my.common.blockInited@rnd", '1');
$tester->loadModel('setting')->setItem("user1.my.common.blockInited@rnd", '1');

$blockTest = new blockModelTest();

r($blockTest->getBlockInitStatusTest('my')) && p('') && e('1');
r($blockTest->getBlockInitStatusTest('product')) && p('') && e('0');
r($blockTest->getBlockInitStatusTest('')) && p('') && e('0');
r($blockTest->getBlockInitStatusTest('nonexistent_dashboard')) && p('') && e('0');
r($blockTest->getBlockInitStatusTest('project')) && p('') && e('0');

su('user1');
r($blockTest->getBlockInitStatusTest('my')) && p('') && e('1');

su('user2');
r($blockTest->getBlockInitStatusTest('my')) && p('') && e('0');