#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildIssuelinkData();
timeout=0
cid=15815

- 执行convertTest模块的buildIssuelinkDataTest方法，参数是array 属性linktype @blocks
- 执行convertTest模块的buildIssuelinkDataTest方法，参数是array 属性linktype @~~
- 执行convertTest模块的buildIssuelinkDataTest方法，参数是array 属性source @~~
- 执行convertTest模块的buildIssuelinkDataTest方法，参数是array 属性destination @~~
- 执行convertTest模块的buildIssuelinkDataTest方法，参数是array 属性linktype @relates&to

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->buildIssuelinkDataTest(array('id' => 1, 'linktype' => 'blocks', 'source' => 'ISSUE-001', 'destination' => 'ISSUE-002'))) && p('linktype') && e('blocks');
r($convertTest->buildIssuelinkDataTest(array('id' => 2))) && p('linktype') && e('~~');
r($convertTest->buildIssuelinkDataTest(array('id' => 3, 'linktype' => '', 'source' => '', 'destination' => ''))) && p('source') && e('~~');
r($convertTest->buildIssuelinkDataTest(array('id' => 4, 'linktype' => null, 'source' => null, 'destination' => null))) && p('destination') && e('~~');
r($convertTest->buildIssuelinkDataTest(array('id' => 5, 'linktype' => 'relates&to', 'source' => 'ISSUE<001>', 'destination' => 'ISSUE"002"'))) && p('linktype') && e('relates&to');