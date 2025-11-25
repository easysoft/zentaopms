#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1', 'story', 'view' 属性extra @
timeout=0
cid=14964

- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1', 'story', 'view' 属性extra @#1
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1, 2, 3', 'story', 'view'
 - 属性extra @#1
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1', 'bug', 'view' 属性extra @#1
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'5, 10', 'bug', 'view'
 - 属性extra @#5
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'', 'story', 'view' 属性extra @#

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

r($actionTest->processLinkStoryAndBugActionExtraTest('1', 'story', 'view')) && p('extra') && e('#1');
r($actionTest->processLinkStoryAndBugActionExtraTest('1,2,3', 'story', 'view')) && p('extra') && e('#1, #2, #3');
r($actionTest->processLinkStoryAndBugActionExtraTest('1', 'bug', 'view')) && p('extra') && e('#1');
r($actionTest->processLinkStoryAndBugActionExtraTest('5,10', 'bug', 'view')) && p('extra') && e('#5, #10');
r($actionTest->processLinkStoryAndBugActionExtraTest('', 'story', 'view')) && p('extra') && e('#');