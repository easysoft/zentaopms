#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel->getPairs();
timeout=0
cid=17349

- 获取type为空的流水线信息属性1 @gitLab
- 获取type为gitlab的流水线信息属性1 @gitLab
- 获取type为test的流水线信息 @0
- 获取type为空的流水线信息 @20
- 获取type为gitlab的流水线信息 @4
- 获取type为test的流水线信息 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('pipeline')->loadYaml('pipeline')->gen(20);

$types = array('', 'gitlab', 'test');

$pipelineTester = new pipelineModelTest();
r($pipelineTester->getPairsTest($types[0])) && p('1') && e('gitLab'); // 获取type为空的流水线信息
r($pipelineTester->getPairsTest($types[1])) && p('1') && e('gitLab'); // 获取type为gitlab的流水线信息
r($pipelineTester->getPairsTest($types[2])) && p(0)   && e('0');      // 获取type为test的流水线信息

r(count($pipelineTester->getPairsTest($types[0]))) && p(0) && e('20'); // 获取type为空的流水线信息
r(count($pipelineTester->getPairsTest($types[1]))) && p(0) && e('4');  // 获取type为gitlab的流水线信息
r(count($pipelineTester->getPairsTest($types[2]))) && p(0) && e('0');  // 获取type为test的流水线信息