#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processLinkStoryAndBugActionExtra();
timeout=0
cid=0

- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1', 'story', 'view' 属性extra @*<a href='#'>#1 </a>*
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1, 2', 'bug', 'view' 属性extra @*<a href='#'>#1 </a>, <a href='#'>#2 </a>*
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'3', 'task', 'view' 属性extra @*<a href='#'>#3 </a>*
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'', 'story', 'view' 属性extra @
- 执行actionTest模块的processLinkStoryAndBugActionExtraTest方法，参数是'1, 2, 3', 'story', 'view' 属性extra @*<a href='#'>#1 </a>, <a href='#'>#2 </a>, <a href='#'>#3 </a>*

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

r($actionTest->processLinkStoryAndBugActionExtraTest('1', 'story', 'view')) && p('extra') && e("*<a href='#'>#1 </a>*");
r($actionTest->processLinkStoryAndBugActionExtraTest('1,2', 'bug', 'view')) && p('extra') && e("*<a href='#'>#1 </a>, <a href='#'>#2 </a>*");
r($actionTest->processLinkStoryAndBugActionExtraTest('3', 'task', 'view')) && p('extra') && e("*<a href='#'>#3 </a>*");
r($actionTest->processLinkStoryAndBugActionExtraTest('', 'story', 'view')) && p('extra') && e('');
r($actionTest->processLinkStoryAndBugActionExtraTest('1,2,3', 'story', 'view')) && p('extra') && e("*<a href='#'>#1 </a>, <a href='#'>#2 </a>, <a href='#'>#3 </a>*");