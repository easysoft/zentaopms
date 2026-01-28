#!/usr/bin/env php
<?php

/**

title=测试 blockZen::getAvailableModules();
timeout=0
cid=15240

- 执行blockTest模块的getAvailableModulesTest方法，参数是'my'  @7
- 执行blockTest模块的getAvailableModulesTest方法，参数是'product'  @0
- 执行blockTest模块的getAvailableModulesTest方法，参数是'project'  @0
- 执行blockTest模块的getAvailableModulesTest方法，参数是'execution'  @0
- 执行blockTest模块的getAvailableModulesTest方法，参数是'qa'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$blockTest = new blockZenTest();

r(count($blockTest->getAvailableModulesTest('my'))) && p() && e('7');
r(count($blockTest->getAvailableModulesTest('product'))) && p() && e('0');
r(count($blockTest->getAvailableModulesTest('project'))) && p() && e('0');
r(count($blockTest->getAvailableModulesTest('execution'))) && p() && e('0');
r(count($blockTest->getAvailableModulesTest('qa'))) && p() && e('0');