#!/usr/bin/env php
<?php

/**

title=- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1'属性extra @
timeout=0
cid=14964

- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1', 'story', 'view' 属性extra @#1
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1, 2, 3', 'story', 'view'
 - 属性extra @#1
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'100', 'bug', 'view' 属性extra @#100
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'100, 101, 102, 103', 'bug', 'view'
 - 属性extra @#100
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'', 'story', 'view' 属性extra @#

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$actionTest = new actionTaoTest();

r($actionTest->processLinkStoryAndBugActionExtraTest('1', 'story', 'view')) && p('extra') && e('#1');
r($actionTest->processLinkStoryAndBugActionExtraTest('1,2,3', 'story', 'view')) && p('extra') && e('#1, #2, #3');
r($actionTest->processLinkStoryAndBugActionExtraTest('100', 'bug', 'view')) && p('extra') && e('#100');
r($actionTest->processLinkStoryAndBugActionExtraTest('100,101,102,103', 'bug', 'view')) && p('extra') && e('#100, #101, #102, #103');
r($actionTest->processLinkStoryAndBugActionExtraTest('', 'story', 'view')) && p('extra') && e('#');