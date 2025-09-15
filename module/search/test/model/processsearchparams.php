#!/usr/bin/env php
<?php

/**

title=测试 searchModel::processSearchParams();
timeout=0
cid=0

- 执行searchTest模块的processSearchParamsTest方法，参数是'story', false  @string
- 执行searchTest模块的processSearchParamsTest方法，参数是'story', true  @string
- 执行searchTest模块的processSearchParamsTest方法，参数是'invalidmodule', false  @0
- 执行searchTest模块的processSearchParamsTest方法，参数是'', false  @0
- 执行searchTest模块的processSearchParamsTest方法，参数是'bug', false  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

// Setup session data for testing
$_SESSION['storySearchFunc'] = array(
    'funcModel' => 'story',
    'funcName' => 'buildSearchConfig',
    'funcArgs' => array('queryID' => 0, 'actionURL' => 'test')
);

$_SESSION['storysearchParams'] = array(
    'module' => 'story',
    'fields' => array('title' => 'Title'),
    'params' => array('title' => array('operator' => 'include', 'control' => 'input'))
);

r($searchTest->processSearchParamsTest('story', false)) && p() && e('string');
r($searchTest->processSearchParamsTest('story', true)) && p() && e('string');
r($searchTest->processSearchParamsTest('invalidmodule', false)) && p() && e('0');
r($searchTest->processSearchParamsTest('', false)) && p() && e('0');
r($searchTest->processSearchParamsTest('bug', false)) && p() && e('0');