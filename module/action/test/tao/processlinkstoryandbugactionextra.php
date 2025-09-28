#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao::processLinkStoryAndBugActionExtra();
cid=0

- 测试步骤1：正常单个ID处理 >> 期望生成带链接的HTML
- 测试步骤2：多个ID逗号分隔处理 >> 期望生成多个链接的HTML
- 测试步骤3：空字符串处理 >> 期望返回空字符串
- 测试步骤4：单个ID带空格处理 >> 期望正确处理空格
- 测试步骤5：多个ID包含空格处理 >> 期望正确处理所有ID

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$actionTest = new actionTest();

r($actionTest->processLinkStoryAndBugActionExtraTest('1', 'story', 'view')) && p('extra') && e("<a href='/' data-size='lg' data-toggle='modal'>#1 </a>");
r($actionTest->processLinkStoryAndBugActionExtraTest('1,2', 'bug', 'view')) && p('extra') && e("<a href='/' data-size='lg' data-toggle='modal'>#1 </a>, <a href='/' data-size='lg' data-toggle='modal'>#2 </a>");
r($actionTest->processLinkStoryAndBugActionExtraTest('', 'story', 'view')) && p('extra') && e('');
r($actionTest->processLinkStoryAndBugActionExtraTest(' 1 ', 'story', 'view')) && p('extra') && e("<a href='/' data-size='lg' data-toggle='modal'>#1 </a>");
r($actionTest->processLinkStoryAndBugActionExtraTest('1, 2, 3', 'story', 'view')) && p('extra') && e("<a href='/' data-size='lg' data-toggle='modal'>#1 </a>, <a href='/' data-size='lg' data-toggle='modal'>#2 </a>, <a href='/' data-size='lg' data-toggle='modal'>#3 </a>");