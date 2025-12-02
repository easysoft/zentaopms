#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createResolution();
timeout=0
cid=15844

- 执行convertTest模块的createResolutionTest方法  @0
- 执行convertTest模块的createResolutionTest方法，参数是'bug_resolution'  @array
- 执行convertTest模块的createResolutionTest方法，参数是'story_reason'  @array
- 执行convertTest模块的createResolutionTest方法，参数是'ticket_closed_reason'  @array
- 执行convertTest模块的createResolutionTest方法，参数是'invalid_key'  @0
- 执行convertTest模块的createResolutionTest方法，参数是'no_resolution'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

r($convertTest->createResolutionTest()) && p() && e('0');
r($convertTest->createResolutionTest('bug_resolution')) && p() && e('array');
r($convertTest->createResolutionTest('story_reason')) && p() && e('array');
r($convertTest->createResolutionTest('ticket_closed_reason')) && p() && e('array');
r($convertTest->createResolutionTest('invalid_key')) && p() && e('0');
r($convertTest->createResolutionTest('no_resolution')) && p() && e('0');