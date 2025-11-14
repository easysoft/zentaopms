#!/usr/bin/env php
<?php

/**

title=测试 blockZen::getAvailableParams();
timeout=0
cid=15241

- 执行blockTest模块的getAvailableParamsTest方法，参数是'', 'task' 第type条的name属性 @类型
- 执行blockTest模块的getAvailableParamsTest方法，参数是'', 'bug' 第type条的name属性 @类型
- 执行blockTest模块的getAvailableParamsTest方法，参数是'', 'story' 第type条的name属性 @类型
- 执行blockTest模块的getAvailableParamsTest方法，参数是'', 'nonexistent'  @0
- 执行blockTest模块的getAvailableParamsTest方法，参数是'assigntome', 'todo' 第count条的name属性 @数量
- 执行blockTest模块的getAvailableParamsTest方法，参数是'product', 'statistic' 第type条的name属性 @类型
- 执行blockTest模块的getAvailableParamsTest方法，参数是'assigntome', 'list' 第count条的name属性 @数量

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

$blockTest = new blockTest();

r($blockTest->getAvailableParamsTest('', 'task')) && p('type:name') && e('类型');
r($blockTest->getAvailableParamsTest('', 'bug')) && p('type:name') && e('类型');
r($blockTest->getAvailableParamsTest('', 'story')) && p('type:name') && e('类型');
r($blockTest->getAvailableParamsTest('', 'nonexistent')) && p() && e('0');
r($blockTest->getAvailableParamsTest('assigntome', 'todo')) && p('count:name') && e('数量');
r($blockTest->getAvailableParamsTest('product', 'statistic')) && p('type:name') && e('类型');
r($blockTest->getAvailableParamsTest('assigntome', 'list')) && p('count:name') && e('数量');