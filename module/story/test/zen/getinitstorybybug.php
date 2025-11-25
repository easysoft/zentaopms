#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getInitStoryByBug();
timeout=0
cid=18689

- 执行storyTest模块的getInitStoryByBugTest方法，参数是1, clone $initStory
 - 属性product @1
 - 属性source @bug
 - 属性pri @1
- 执行storyTest模块的getInitStoryByBugTest方法，参数是0, clone $initStory
 - 属性product @0
 - 属性source @~~
- 执行storyTest模块的getInitStoryByBugTest方法，参数是2, clone $initStory
 - 属性source @bug
 - 属性title @BUG2
- 执行storyTest模块的getInitStoryByBugTest方法，参数是3, clone $initStory
 - 属性source @bug
 - 属性spec @<p>【步骤】</p><br/><p>【结果】</p><br/><p>【期望】</p><br/>
- 执行storyTest模块的getInitStoryByBugTest方法，参数是4, clone $initStory
 - 属性product @2
 - 属性source @bug

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('bug')->loadYaml('bug_getinitstorybybug', false, 2)->gen(10);
zenData('user')->gen(5);

su('admin');

$storyTest = new storyZenTest();

$initStory = new stdclass();
$initStory->product = 0;
$initStory->source = '';
$initStory->title = '';
$initStory->keywords = '';
$initStory->spec = '';
$initStory->pri = '';
$initStory->mailto = '';

r($storyTest->getInitStoryByBugTest(1, clone $initStory)) && p('product,source,pri') && e('1,bug,1');
r($storyTest->getInitStoryByBugTest(0, clone $initStory)) && p('product,source') && e('0,~~');
r($storyTest->getInitStoryByBugTest(2, clone $initStory)) && p('source,title') && e('bug,BUG2');
r($storyTest->getInitStoryByBugTest(3, clone $initStory)) && p('source,spec') && e('bug,<p>【步骤】</p><br/><p>【结果】</p><br/><p>【期望】</p><br/>');
r($storyTest->getInitStoryByBugTest(4, clone $initStory)) && p('product,source') && e('2,bug');