#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printExecutionListBlock();
timeout=0
cid=0

- 执行blockTest模块的printExecutionListBlockTest方法 属性hasExecutions @true
- 执行blockTest模块的printExecutionListBlockTest方法 属性hasExecutions @false
- 执行blockTest模块的printExecutionListBlockTest方法 属性hasExecutions @true
- 执行blockTest模块的printExecutionListBlockTest方法 属性hasExecutions @true
- 执行blockTest模块的printExecutionListBlockTest方法 属性hasExecutions @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

$blockTest = new blockTest();

r($blockTest->printExecutionListBlockTest((object)array('params' => (object)array('type' => 'doing', 'count' => 10), 'dashboard' => 'project'))) && p('hasExecutions') && e('true');
r($blockTest->printExecutionListBlockTest((object)array('params' => (object)array('type' => 'invalid@type', 'count' => 10), 'dashboard' => 'project'))) && p('hasExecutions') && e('false');
r($blockTest->printExecutionListBlockTest((object)array('params' => (object)array('count' => 10), 'dashboard' => 'project'))) && p('hasExecutions') && e('true');
r($blockTest->printExecutionListBlockTest((object)array('params' => (object)array('type' => 'all'), 'dashboard' => 'project'))) && p('hasExecutions') && e('true');
r($blockTest->printExecutionListBlockTest((object)array('params' => (object)array('type' => 'wait', 'count' => 5), 'dashboard' => 'my'))) && p('hasExecutions') && e('true');