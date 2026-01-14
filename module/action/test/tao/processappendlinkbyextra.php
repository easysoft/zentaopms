#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processAppendLinkByExtra();
timeout=0
cid=14959

- 执行actionTest模块的processAppendLinkByExtraTest方法，参数是'task:1' 属性extra @task
- 执行actionTest模块的processAppendLinkByExtraTest方法，参数是'story:1|comment' 属性extra @story
- 执行actionTest模块的processAppendLinkByExtraTest方法，参数是'normalextra' 属性appendLink @~~
- 执行actionTest模块的processAppendLinkByExtraTest方法，参数是'story:1', 'todo' 属性extra @story
- 执行actionTest模块的processAppendLinkByExtraTest方法，参数是'task:999' 属性appendLink @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('task')->gen(10);
zenData('story')->gen(10);
zenData('bug')->gen(10);

su('admin');

$actionTest = new actionTaoTest();

r($actionTest->processAppendLinkByExtraTest('task:1')) && p('extra') && e('task');
r($actionTest->processAppendLinkByExtraTest('story:1|comment')) && p('extra') && e('story');
r($actionTest->processAppendLinkByExtraTest('normalextra')) && p('appendLink') && e('~~');
r($actionTest->processAppendLinkByExtraTest('story:1', 'todo')) && p('extra') && e('story');
r($actionTest->processAppendLinkByExtraTest('task:999')) && p('appendLink') && e('~~');